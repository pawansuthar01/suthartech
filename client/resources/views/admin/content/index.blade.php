@extends('layouts.admin')
@section('title', $label)
@section('content')
    <div class="admin-page-heading"><div><h2>{{ $label }}</h2><p>Add, update, publish, or remove website content.</p></div><a class="admin-button admin-button-primary" href="{{ route('admin.'.$module.'.create') }}"><span class="material-symbols-outlined" aria-hidden="true">add</span>Add {{ rtrim($label, 's') }}</a></div>
    <section class="admin-panel">
        <div class="admin-panel-header"><h3>All {{ strtolower($label) }}</h3><form class="admin-filter" method="GET"><input aria-label="Search {{ strtolower($label) }}" name="search" value="{{ request('search') }}" placeholder="Search"><select aria-label="Filter by status" name="status"><option value="">All statuses</option><option value="published" @selected(request('status') === 'published')>Published</option><option value="draft" @selected(request('status') === 'draft')>Draft</option></select><button class="admin-button" type="submit">Filter</button></form></div>
        @if ($records->isEmpty()) <div class="admin-empty">No records yet. Add an item when its details are ready.</div>
        @else
            <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Title</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead><tbody>
                @foreach ($records as $record)
                    @php($recordTitle = data_get($record, 'title') ?? data_get($record, 'client_name'))
                    <tr><td><strong>{{ $recordTitle }}</strong>@if($record->slug ?? false)<br><small>/{{ $record->slug }}</small>@endif</td><td><span class="admin-status {{ ($record->status ?? '') === 'published' ? 'admin-status-published' : '' }}">{{ $record->status ?? '—' }}</span></td><td>{{ $record->updated_at?->format('M j, Y') }}</td><td><div class="admin-table-actions"><a class="admin-button" href="{{ route('admin.'.$module.'.edit', $record) }}">Edit</a><form method="POST" action="{{ route('admin.'.$module.'.destroy', $record) }}" onsubmit="return confirm('Delete this item?')">@csrf @method('DELETE')<button class="admin-button admin-button-danger" type="submit">Delete</button></form></div></td></tr>
                @endforeach
            </tbody></table></div>
            @if($records->hasPages())<div class="admin-pagination">{{ $records->links() }}</div>@endif
        @endif
    </section>
@endsection
