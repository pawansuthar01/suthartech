@extends('layouts.admin')
@section('title', 'Inquiries')
@section('content')
    <div class="admin-page-heading"><div><h2>Project inquiries</h2><p>Private messages submitted through the contact form.</p></div></div>
    <section class="admin-panel">
        @if ($inquiries->isEmpty()) <div class="admin-empty">No inquiries received yet.</div>
        @else <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Name</th><th>Email</th><th>Project type</th><th>Status</th><th>Received</th><th></th></tr></thead><tbody>
            @foreach($inquiries as $inquiry)<tr><td>{{ $inquiry->name }}</td><td>{{ $inquiry->email }}</td><td>{{ $inquiry->project_type }}</td><td><span class="admin-status {{ $inquiry->read_at ? '' : 'admin-status-published' }}">{{ $inquiry->read_at ? 'Read' : 'New' }}</span></td><td>{{ $inquiry->created_at->format('M j, Y') }}</td><td><a class="admin-button" href="{{ route('admin.inquiries.show', $inquiry) }}">View</a></td></tr>@endforeach
        </tbody></table></div><div class="admin-pagination">{{ $inquiries->links() }}</div>@endif
    </section>
@endsection
