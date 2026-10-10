<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SutharTechContentApi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FixedPageController extends Controller
{
    public function __construct(private readonly SutharTechContentApi $api) {}

    public function index(): View
    {
        return view('admin.website-content.pages', ['pages' => $this->api->fixedPages()]);
    }

    public function edit(string $slug): View
    {
        $page = collect($this->api->fixedPages())->firstWhere('slug', $slug);
        abort_unless($page, 404);
        return view('admin.website-content.page-form', ['page' => $page]);
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        $data = $request->validate(['meta_title' => ['nullable', 'string', 'max:255'], 'meta_description' => ['nullable', 'string', 'max:320']]);
        $this->api->saveFixedPage($slug, $data);
        return to_route('admin.website-pages.index')->with('success', 'Page SEO updated. Its route and layout are unchanged.');
    }
}
