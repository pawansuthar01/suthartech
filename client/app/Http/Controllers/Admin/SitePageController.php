<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SitePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SitePageController extends Controller
{
    public function index(Request $request): View
    {
        $records = SitePage::query()
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderBy('sort_order')->latest()->paginate(15)->withQueryString();

        return view('admin.content.index', ['module' => 'pages', 'label' => 'Pages', 'records' => $records]);
    }

    public function create(): View
    {
        return view('admin.content.form', ['module' => 'pages', 'label' => 'Page', 'item' => new SitePage(['status' => 'draft'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->requirePublicCopy($data);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title']);
        SitePage::create($data);

        return to_route('admin.pages.index')->with('success', 'Page saved as '.$data['status'].'.');
    }

    public function edit(SitePage $page): View
    {
        return view('admin.content.form', ['module' => 'pages', 'label' => 'Page', 'item' => $page]);
    }

    public function update(Request $request, SitePage $page): RedirectResponse
    {
        $data = $this->validated($request, $page);
        $this->requirePublicCopy($data);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title'], $page->id);
        $page->update($data);

        return to_route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(SitePage $page): RedirectResponse
    {
        abort_if(in_array($page->slug, ['home', 'about'], true), 422, 'Core page records cannot be deleted.');
        $page->delete();

        return to_route('admin.pages.index')->with('success', 'Page deleted.');
    }

    private function validated(Request $request, ?SitePage $page = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('site_pages', 'slug')->ignore($page?->id)],
            'hero_eyebrow' => ['nullable', 'string', 'max:120'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string', 'max:700'],
            'body' => ['nullable', 'string', 'max:50000'],
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ]);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'page';
        $slug = $base;
        for ($number = 2; SitePage::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists(); $number++) {
            $slug = $base.'-'.$number;
        }

        return $slug;
    }

    private function requirePublicCopy(array $data): void
    {
        if ($data['status'] === 'published' && blank($data['hero_title'] ?? null) && blank($data['hero_description'] ?? null) && blank($data['body'] ?? null)) {
            throw ValidationException::withMessages(['hero_title' => 'Add page content before publishing.']);
        }
    }
}
