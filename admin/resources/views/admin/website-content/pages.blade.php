@extends('layouts.admin')
@section('title', 'Fixed Website Pages')
@section('content')
<h2 class="mb-1">Fixed website pages</h2><p class="text-muted mb-4">Edit search metadata for existing routes only. Page names, URLs, and layouts stay locked.</p>
<div class="card"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Page</th><th>Route</th><th>Search title</th><th></th></tr></thead><tbody>@foreach($pages as $page)<tr><td>{{ $page['title'] }}</td><td>/{{ $page['slug']==='home'?'':$page['slug'] }}</td><td>{{ $page['meta_title'] ?: 'Using page default' }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.website-pages.edit',$page['slug']) }}">Edit SEO</a></td></tr>@endforeach</tbody></table></div></div>
@endsection
