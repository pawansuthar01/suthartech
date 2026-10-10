<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Slider;
use App\Services\SutharTechContentApi;

class DashboardController extends Controller
{
    public function index(SutharTechContentApi $contentApi)
    {
        $stats = [
            'blogs' => Blog::count(),
            'services' => $contentApi->list('services')['total'] ?? 0,
            'galleries' => Gallery::count(),
            // The local single-process server cannot call the client app on the same port synchronously.
            'pages' => config('app.env') === 'local' ? null : count($contentApi->fixedPages()),
            'sliders' => Slider::count(),
            'messages' => Contact::count(),
            'unread_messages' => Contact::where('is_read', false)->count(),
        ];

        $recentMessages = Contact::latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentMessages'));
    }
}
