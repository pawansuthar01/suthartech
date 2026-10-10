<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $blogs = Blog::query()
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.content.index', ['module' => 'blogs', 'label' => 'Blog posts', 'records' => $blogs]);
    }

    public function create(): View
    {
        return view('admin.content.form', ['module' => 'blogs', 'label' => 'Blog post', 'item' => new Blog(['status' => 'draft'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title']);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('blogs', 'public');
        }
        unset($data['image']);
        Blog::create($data);

        return to_route('admin.blogs.index')->with('success', 'Blog post saved as '.$data['status'].'.');
    }

    public function edit(Blog $blog): View
    {
        return view('admin.content.form', ['module' => 'blogs', 'label' => 'Blog post', 'item' => $blog]);
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $data = $this->validated($request, $blog);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title'], $blog->id);
        $data['published_at'] = $data['status'] === 'published' ? ($blog->published_at ?? now()) : null;
        $oldImage = $blog->image_path;
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('blogs', 'public');
        }
        unset($data['image']);
        $blog->update($data);
        if ($request->hasFile('image') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return to_route('admin.blogs.index')->with('success', 'Blog post updated.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $image = $blog->image_path;
        $blog->delete();
        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return to_route('admin.blogs.index')->with('success', 'Blog post deleted.');
    }

    private function validated(Request $request, ?Blog $blog = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'topic' => ['nullable', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('blogs', 'slug')->ignore($blog?->id)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:50000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ]);

        $data['topic'] = Str::slug($data['topic'] ?? 'general') ?: 'general';

        return $data;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'blog';
        $slug = $base;
        for ($number = 2; Blog::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists(); $number++) {
            $slug = $base.'-'.$number;
        }

        return $slug;
    }
}
