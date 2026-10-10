<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SutharTechContentApi;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private readonly SutharTechContentApi $api) {}

    public function index(Request $request)
    {
        $records = $this->api->list('faqs', $request->only('search', 'status', 'page'));
        $faqs = collect($records['data'])->map(fn (array $faq) => (object) ($faq + ['is_active' => $faq['status'] === 'active']));
        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['status'] = $request->boolean('is_active') ? 'active' : 'inactive';
        $this->api->save('faqs', $data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $faq)
    {
        $data = $this->api->get('faqs', $faq);
        $faq = (object) ($data + ['is_active' => $data['status'] === 'active']);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function toggleStatus(int $faq)
    {
        $data = $this->api->get('faqs', $faq);
        $data['status'] = $data['status'] === 'active' ? 'inactive' : 'active';
        $this->api->save('faqs', $data, $faq);

        return back()->with('success', 'FAQ status updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $faq)
    {
        $data = $this->validated($request);
        $data['status'] = $request->boolean('is_active') ? 'active' : 'inactive';
        $this->api->save('faqs', $data, $faq);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $faq)
    {
        $this->api->delete('faqs', $faq);
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:10000'],
            'page' => ['required', 'in:home,contact'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
