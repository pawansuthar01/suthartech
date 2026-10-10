<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $records = Project::query()
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderBy('sort_order')->latest()->paginate(15)->withQueryString();

        return view('admin.content.index', ['module' => 'projects', 'label' => 'Projects', 'records' => $records]);
    }

    public function create(): View
    {
        return view('admin.content.form', ['module' => 'projects', 'label' => 'Project', 'item' => new Project(['status' => 'draft'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title']);
        $data['technologies'] = $this->splitTechnologies($data['technologies'] ?? '');
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('projects', 'public');
        }
        unset($data['image']);
        Project::create($data);

        return to_route('admin.projects.index')->with('success', 'Project saved as '.$data['status'].'.');
    }

    public function edit(Project $project): View
    {
        return view('admin.content.form', ['module' => 'projects', 'label' => 'Project', 'item' => $project]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validated($request, $project);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title'], $project->id);
        $data['technologies'] = $this->splitTechnologies($data['technologies'] ?? '');
        $oldImage = $project->image_path;
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('projects', 'public');
        }
        unset($data['image']);
        $project->update($data);
        if ($request->hasFile('image') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return to_route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $image = $project->image_path;
        $project->delete();
        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return to_route('admin.projects.index')->with('success', 'Project deleted.');
    }

    private function validated(Request $request, ?Project $project = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('projects', 'slug')->ignore($project?->id)],
            'category' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:50000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'technologies' => ['nullable', 'string', 'max:2000'],
            'project_url' => ['nullable', 'url:http,https', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ]);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'project';
        $slug = $base;
        for ($number = 2; Project::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists(); $number++) {
            $slug = $base.'-'.$number;
        }

        return $slug;
    }

    private function splitTechnologies(string $technologies): array
    {
        return array_values(array_filter(array_unique(array_map('trim', preg_split('/[,\r\n]+/', $technologies) ?: []))));
    }
}
