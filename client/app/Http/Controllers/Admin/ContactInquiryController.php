<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactInquiryController extends Controller
{
    public function index(): View
    {
        return view('admin.inquiries.index', [
            'inquiries' => ContactInquiry::latest()->paginate(20),
        ]);
    }

    public function show(ContactInquiry $inquiry): View
    {
        $inquiry->update(['read_at' => $inquiry->read_at ?? now()]);

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function attachment(ContactInquiry $inquiry): StreamedResponse
    {
        abort_unless($inquiry->attachment_path && Storage::disk('local')->exists($inquiry->attachment_path), 404);

        return Storage::disk('local')->download($inquiry->attachment_path);
    }

    public function destroy(ContactInquiry $inquiry): RedirectResponse
    {
        $attachment = $inquiry->attachment_path;
        $inquiry->delete();
        if ($attachment) {
            Storage::disk('local')->delete($attachment);
        }

        return to_route('admin.inquiries.index')->with('success', 'Inquiry deleted.');
    }
}
