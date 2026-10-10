<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CertificationController extends Controller
{
    public function index(Request $request): View
    {
        $records = Certification::query()
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderBy('sort_order')->latest()->paginate(15)->withQueryString();

        return view('admin.content.index', ['module' => 'certifications', 'label' => 'Certificates', 'records' => $records]);
    }

    public function create(): View
    {
        return view('admin.content.form', ['module' => 'certifications', 'label' => 'Certificate', 'item' => new Certification(['status' => 'draft'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('certifications', 'public');
        }
        unset($data['image']);
        Certification::create($data);

        return to_route('admin.certifications.index')->with('success', 'Certificate saved as '.$data['status'].'.');
    }

    public function edit(Certification $certification): View
    {
        return view('admin.content.form', ['module' => 'certifications', 'label' => 'Certificate', 'item' => $certification]);
    }

    public function update(Request $request, Certification $certification): RedirectResponse
    {
        $data = $this->validated($request, $certification);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title'], $certification->id);
        $oldImage = $certification->image_path;
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('certifications', 'public');
        }
        unset($data['image']);
        $certification->update($data);
        if ($request->hasFile('image') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return to_route('admin.certifications.index')->with('success', 'Certificate updated.');
    }

    public function destroy(Certification $certification): RedirectResponse
    {
        $image = $certification->image_path;
        $certification->delete();
        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return to_route('admin.certifications.index')->with('success', 'Certification deleted.');
    }

    private function validated(Request $request, ?Certification $certification = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('certifications', 'slug')->ignore($certification?->id)],
            'issuer' => ['required', 'string', 'max:190'],
            'credential_id' => ['nullable', 'string', 'max:190', function ($attribute, $value, $fail) use ($certification) {
                if (filled($value) && Certification::whereRaw('LOWER(TRIM(credential_id)) = ?', [Str::lower(trim($value))])
                    ->when($certification, fn ($query) => $query->whereKeyNot($certification->id))
                    ->exists()) {
                    $fail('This certificate ID is already assigned to another certificate.');
                }
            }],
            'credential_url' => ['nullable', 'url:http,https', 'max:255'],
            'issued_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:issued_at'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ]);

        if (filled($data['credential_id'] ?? null)) {
            $data['credential_id'] = trim($data['credential_id']);
        }

        return $data;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'certification';
        $slug = $base;
        for ($number = 2; Certification::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists(); $number++) {
            $slug = $base.'-'.$number;
        }

        return $slug;
    }
}
