<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Certification;
use App\Models\Faq;
use App\Models\Project;
use App\Models\Service;
use App\Models\SitePage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    private const PAGE_SLUGS = ['about', 'blogs', 'certifications', 'contact', 'home', 'projects', 'services', 'testimonials'];

    public function fixedPages(): JsonResponse
    {
        return response()->json(collect(self::PAGE_SLUGS)->map(function (string $slug): array {
            $page = SitePage::where('slug', $slug)->first();
            return ['slug' => $slug, 'title' => $page?->title ?: Str::title(str_replace('-', ' ', $slug)), 'meta_title' => $page?->meta_title, 'meta_description' => $page?->meta_description];
        }));
    }

    public function updateFixedPage(Request $request, string $slug): JsonResponse
    {
        abort_unless(in_array($slug, self::PAGE_SLUGS, true), 404);
        $data = $request->validate(['meta_title' => ['nullable', 'string', 'max:255'], 'meta_description' => ['nullable', 'string', 'max:320']]);
        $page = SitePage::firstOrNew(['slug' => $slug]);
        $page->fill($data + ['title' => $page->title ?: Str::title(str_replace('-', ' ', $slug)), 'status' => 'active']);
        $page->save();

        return response()->json(['slug' => $page->slug, 'meta_title' => $page->meta_title, 'meta_description' => $page->meta_description]);
    }

    public function index(Request $request, string $type): JsonResponse
    {
        $model = $this->model($type);
        $records = $model::query()
            ->when($request->filled('search'), fn ($query) => $query->where(match ($type) { 'feedback' => 'client_name', 'faqs' => 'question', default => 'title' }, 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($type === 'blogs', fn ($query) => $query->latest())
            ->when($type === 'faqs', fn ($query) => $query->orderBy('sort_order')->orderBy('id'))
            ->when(! in_array($type, ['blogs', 'faqs'], true), fn ($query) => $query->orderBy('sort_order')->latest())
            ->paginate(15)->withQueryString();
        if ($type === 'feedback') {
            $records->getCollection()->transform(fn ($item) => array_merge($item->toArray(), ['title' => $item->client_name]));
        }

        return response()->json($records);
    }

    public function show(string $type, int $record): JsonResponse
    {
        return response()->json($this->model($type)::findOrFail($record));
    }

    public function store(Request $request, string $type): JsonResponse
    {
        $model = $this->model($type);
        $data = $this->validated($request, $type);
        if ($type === 'pages' && ! in_array($data['slug'] ?? null, self::PAGE_SLUGS, true)) {
            abort(422, 'Only existing fixed pages can be edited.');
        }
        if (! in_array($type, ['feedback', 'faqs'], true)) {
            $data['slug'] = $this->slug($model, filled($data['slug'] ?? null) ? $data['slug'] : $data['title']);
        }
        if ($type === 'blogs') {
            $data['topic'] = Str::slug($data['topic'] ?? 'general') ?: 'general';
            $data['published_at'] = $data['status'] === 'active' ? now() : null;
        }
        if (! in_array($type, ['feedback', 'faqs'], true)) {
            $this->storeImage($request, $data, $type);
        }
        $item = $model::create($data);

        return response()->json($item, 201);
    }

    public function update(Request $request, string $type, int $record): JsonResponse
    {
        $model = $this->model($type);
        $item = $model::findOrFail($record);
        $data = $this->validated($request, $type, $item->id);
        $oldImage = $item->image_path ?? null;
        if ($type === 'pages' && (! in_array($item->slug, self::PAGE_SLUGS, true) || ($data['slug'] ?? $item->slug) !== $item->slug)) {
            abort(422, 'Fixed page slugs cannot be changed.');
        }
        if (! in_array($type, ['pages', 'feedback', 'faqs'], true)) {
            $data['slug'] = $this->slug($model, filled($data['slug'] ?? null) ? $data['slug'] : $data['title'], $item->id);
        }
        if ($type === 'blogs' && $data['status'] !== 'active') {
            $data['published_at'] = null;
        } elseif ($type === 'blogs' && ! $item->published_at) {
            $data['published_at'] = now();
        }
        if ($type === 'blogs' && array_key_exists('topic', $data)) {
            $data['topic'] = Str::slug($data['topic']) ?: 'general';
        }
        if ($type !== 'feedback') {
            $this->storeImage($request, $data, $type);
        }
        $item->update($data);
        if (($request->hasFile('image') || $request->boolean('remove_image')) && $oldImage && ! str_starts_with($oldImage, 'images/')) {
            Storage::disk('public')->delete($oldImage);
        }

        return response()->json($item->fresh());
    }

    public function destroy(string $type, int $record): JsonResponse
    {
        $item = $this->model($type)::findOrFail($record);
        abort_if($type === 'pages', 422, 'Fixed page records cannot be deleted.');
        $image = $item->image_path;
        $item->delete();
        if ($image && ! str_starts_with($image, 'images/')) {
            Storage::disk('public')->delete($image);
        }

        return response()->json(['deleted' => true]);
    }

    private function model(string $type): string
    {
        return match ($type) {
            'blogs' => Blog::class,
            'projects' => Project::class,
            'certifications' => Certification::class,
            'services' => Service::class,
            'feedback' => \App\Models\Testimonial::class,
            'faqs' => Faq::class,
            'pages' => SitePage::class,
            default => abort(404),
        };
    }

    private function validated(Request $request, string $type, ?int $ignore = null): array
    {
        if ($type === 'faqs') {
            return $request->validate([
                'question' => ['required', 'string', 'max:255'],
                'answer' => ['required', 'string', 'max:10000'],
                'page' => ['sometimes', 'required', Rule::in(['home', 'contact'])],
                'sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'],
                'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            ]);
        }

        if ($type === 'feedback') {
            return $request->validate([
                'client_name' => ['required', 'string', 'max:190'], 'client_role' => ['nullable', 'string', 'max:190'],
                'company' => ['nullable', 'string', 'max:190'], 'quote' => ['required', 'string', 'max:5000'],
                'sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'], 'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            ]);
        }
        $common = [
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique(match ($type) { 'blogs' => 'blogs', 'projects' => 'projects', 'certifications' => 'certifications', 'services' => 'services', default => 'site_pages' }, 'slug')->ignore($ignore)],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ];
        $specific = match ($type) {
            'blogs' => ['topic' => ['nullable', 'string', 'max:80'], 'excerpt' => ['nullable', 'string', 'max:500'], 'body' => ['required', 'string', 'max:50000'], 'image_alt' => ['nullable', 'string', 'max:255']],
            'projects' => ['category' => ['required', 'string', 'max:120'], 'summary' => ['required', 'string', 'max:500'], 'body' => ['nullable', 'string', 'max:50000'], 'technologies' => ['nullable', 'array'], 'project_url' => ['nullable', 'url:http,https', 'max:255'], 'is_featured' => ['nullable', 'boolean']],
            'certifications' => ['issuer' => ['required', 'string', 'max:190'], 'credential_id' => ['nullable', 'string', 'max:190', function ($attribute, $value, $fail) use ($ignore) {
                if (filled($value) && Certification::whereRaw('LOWER(TRIM(credential_id)) = ?', [Str::lower(trim($value))])
                    ->when($ignore, fn ($query) => $query->whereKeyNot($ignore))
                    ->exists()) {
                    $fail('This certificate ID is already assigned to another certificate.');
                }
            }], 'credential_url' => ['nullable', 'url:http,https', 'max:255'], 'issued_at' => ['nullable', 'date'], 'expires_at' => ['nullable', 'date', 'after_or_equal:issued_at'], 'description' => ['nullable', 'string', 'max:5000']],
            'services' => ['icon' => ['nullable', 'string', 'max:100'], 'description' => ['required', 'string', 'max:700'], 'body' => ['nullable', 'string', 'max:50000'], 'deliverables' => ['nullable', 'array']],
            default => ['hero_eyebrow' => ['nullable', 'string', 'max:120'], 'hero_title' => ['nullable', 'string', 'max:255'], 'hero_description' => ['nullable', 'string', 'max:700'], 'body' => ['nullable', 'string', 'max:50000']],
        };

        $data = $request->validate($common + $specific);
        if ($type === 'certifications' && $data['status'] === 'active') {
            $existing = $ignore ? Certification::find($ignore) : null;
            $existingImage = ! $request->boolean('remove_image') && filled($existing?->image_path);
            $evidence = filled($data['credential_url'] ?? null) || $request->hasFile('image') || $existingImage;
            if (blank($data['credential_id'] ?? null) || blank($data['issued_at'] ?? null) || ! $evidence) {
                throw ValidationException::withMessages([
                    'status' => 'To publish a certificate, add its issuer ID, issue date, and either the issuer verification link or a certificate image.',
                ]);
            }
        }
        if ($type === 'certifications' && filled($data['credential_id'] ?? null)) {
            $data['credential_id'] = trim($data['credential_id']);
        }

        return $data;
    }

    private function slug(string $model, string $value, ?int $ignore = null): string
    {
        $base = Str::slug($value) ?: 'content';
        $slug = $base;
        for ($number = 2; $model::where('slug', $slug)->when($ignore, fn ($query) => $query->whereKeyNot($ignore))->exists(); $number++) {
            $slug = $base.'-'.$number;
        }

        return $slug;
    }

    private function storeImage(Request $request, array &$data, string $type): void
    {
        if (! $request->hasFile('image')) {
            unset($data['image']);
            if ($request->boolean('remove_image')) {
                $data['image_path'] = null;
            }
            return;
        }
        $data['image_path'] = $request->file('image')->store($type, 'public');
        unset($data['image']);
    }
}
