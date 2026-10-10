@extends('layouts.admin')
@section('title', 'Inquiry from '.$inquiry->name)
@section('content')
    <div class="admin-page-heading"><div><h2>Inquiry from {{ $inquiry->name }}</h2><p>Received {{ $inquiry->created_at->format('M j, Y · g:i a') }}</p></div><a class="admin-button" href="{{ route('admin.inquiries.index') }}">All inquiries</a></div>
    <section class="admin-panel" style="padding:22px">
        <dl class="admin-grid-2"><div><dt>Email</dt><dd><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></dd></div><div><dt>Phone</dt><dd>{{ $inquiry->phone ?: 'Not provided' }}</dd></div><div><dt>Company</dt><dd>{{ $inquiry->company ?: 'Not provided' }}</dd></div><div><dt>Project type</dt><dd>{{ $inquiry->project_type }}</dd></div><div><dt>Budget</dt><dd>{{ $inquiry->budget ?: 'Not provided' }}</dd></div></dl>
        <h3>Message</h3><p style="white-space:pre-wrap">{{ $inquiry->message }}</p>
        @if($inquiry->attachment_path)<p><a class="admin-button" href="{{ route('admin.inquiries.attachment', $inquiry) }}">Download private attachment</a></p>@endif
        <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" onsubmit="return confirm('Delete this inquiry and its attachment?')">@csrf @method('DELETE')<button class="admin-button admin-button-danger" type="submit">Delete inquiry</button></form>
    </section>
@endsection
