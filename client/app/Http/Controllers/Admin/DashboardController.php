<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Certification;
use App\Models\ContactInquiry;
use App\Models\Project;
use App\Models\SitePage;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'blogs' => Blog::count(),
                'projects' => Project::count(),
                'feedback' => Testimonial::count(),
                'certifications' => Certification::count(),
                'pages' => SitePage::count(),
                'services' => Service::count(),
                'unread' => ContactInquiry::whereNull('read_at')->count(),
            ],
            'inquiries' => ContactInquiry::latest()->limit(6)->get(),
            'drafts' => Blog::where('status', 'draft')->count()
                + Project::where('status', 'draft')->count()
                + Testimonial::where('status', 'draft')->count()
                + Certification::where('status', 'draft')->count(),
        ]);
    }
}
