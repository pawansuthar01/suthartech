@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <div class="admin-page-heading"><div><h2>Website overview</h2><p>Manage content and see new project inquiries.</p></div></div>
    <section class="admin-welcome-panel">
        <div><h2>Welcome back, {{ auth()->user()->name }}</h2><p>Here is your SutharTech website overview.</p></div>
        <div class="admin-welcome-date"><strong>{{ now()->format('d') }}</strong><span>{{ now()->format('M Y') }}</span></div>
    </section>
    <div class="admin-stats">
        @foreach ([['blogs', 'Blog posts', 'admin.blogs.index'], ['projects', 'Projects', 'admin.projects.index'], ['feedback', 'Feedback', 'admin.feedback.index'], ['certifications', 'Certifications', 'admin.certifications.index'], ['pages', 'Pages', 'admin.pages.index'], ['services', 'Services', 'admin.services.index'], ['unread', 'Unread inquiries', 'admin.inquiries.index'], ['drafts', 'Draft items', 'admin.blogs.index']] as [$key, $label, $url])
            <a class="admin-stat" href="{{ route($url) }}" style="text-decoration:none"><span>{{ $label }}</span><strong>{{ $key === 'drafts' ? $drafts : $counts[$key] }}</strong></a>
        @endforeach
    </div>
    <section class="admin-panel"><div class="admin-panel-header"><h3>Recent inquiries</h3><a class="admin-button" href="{{ route('admin.inquiries.index') }}">All inquiries</a></div>
        @if ($inquiries->isEmpty()) <div class="admin-empty">No inquiries received yet.</div>
        @else <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Name</th><th>Project type</th><th>Received</th><th></th></tr></thead><tbody>
            @foreach ($inquiries as $inquiry)<tr><td>{{ $inquiry->name }} @if(!$inquiry->read_at)<span class="admin-status">New</span>@endif</td><td>{{ $inquiry->project_type }}</td><td>{{ $inquiry->created_at->format('M j, Y') }}</td><td><a class="admin-button" href="{{ route('admin.inquiries.show', $inquiry) }}">View</a></td></tr>@endforeach
        </tbody></table></div>@endif
    </section>
@endsection
