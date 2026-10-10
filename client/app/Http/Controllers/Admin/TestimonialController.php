<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $records = Testimonial::query()
            ->when($request->filled('search'), fn ($query) => $query->where('client_name', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderBy('sort_order')->latest()->paginate(15)->withQueryString();

        return view('admin.content.index', ['module' => 'feedback', 'label' => 'Feedback', 'records' => $records]);
    }

    public function create(): View
    {
        return view('admin.content.form', ['module' => 'feedback', 'label' => 'Feedback', 'item' => new Testimonial(['status' => 'draft'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Testimonial::create($this->validated($request));

        return to_route('admin.feedback.index')->with('success', 'Feedback saved.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.content.form', ['module' => 'feedback', 'label' => 'Feedback', 'item' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->validated($request));

        return to_route('admin.feedback.index')->with('success', 'Feedback updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return to_route('admin.feedback.index')->with('success', 'Feedback deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'client_name' => ['required', 'string', 'max:160'],
            'client_role' => ['nullable', 'string', 'max:160'],
            'company' => ['nullable', 'string', 'max:160'],
            'quote' => ['required', 'string', 'max:2000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);
    }
}
