<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $records = Service::query()
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderBy('sort_order')->latest()->paginate(15)->withQueryString();

        return view('admin.content.index', ['module' => 'services', 'label' => 'Services', 'records' => $records]);
    }

    public function create(): View
    {
        return view('admin.content.form', ['module' => 'services', 'label' => 'Service', 'item' => new Service(['status' => 'draft'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title']);
        $data['deliverables'] = $this->splitLines($data['deliverables'] ?? '');
        Service::create($data);

        return to_route('admin.services.index')->with('success', 'Service saved as '.$data['status'].'.');
    }

    public function edit(Service $service): View
    {
        return view('admin.content.form', ['module' => 'services', 'label' => 'Service', 'item' => $service]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->validated($request, $service);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title'], $service->id);
        $data['deliverables'] = $this->splitLines($data['deliverables'] ?? '');
        $service->update($data);

        return to_route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return to_route('admin.services.index')->with('success', 'Service deleted.');
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('services', 'slug')->ignore($service?->id)],
            'icon' => ['nullable', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:700'],
            'body' => ['nullable', 'string', 'max:50000'],
            'deliverables' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ]);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'service';
        $slug = $base;
        for ($number = 2; Service::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists(); $number++) {
            $slug = $base.'-'.$number;
        }

        return $slug;
    }

    private function splitLines(string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $text) ?: [])));
    }
}
