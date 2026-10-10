@extends('layouts.admin')
@section('title', 'Edit '.$page['title'].' SEO')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h2>{{ $page['title'] }}</h2><p class="text-muted mb-0">/{{ $page['slug']==='home'?'':$page['slug'] }} · URL, content layout, and title remain unchanged.</p></div><a class="btn btn-outline-secondary" href="{{ route('admin.website-pages.index') }}">Back</a></div>
<form class="card" method="POST" action="{{ route('admin.website-pages.update',$page['slug']) }}">@csrf @method('PUT')<div class="card-body"><div class="mb-3"><label class="form-label">Meta title</label><input class="form-control" name="meta_title" maxlength="255" value="{{ old('meta_title',$page['meta_title']??'') }}"></div><div class="mb-3"><label class="form-label">Meta description</label><textarea class="form-control" name="meta_description" rows="3" maxlength="320">{{ old('meta_description',$page['meta_description']??'') }}</textarea></div><button class="btn btn-primary">Save SEO</button></div></form>
@endsection
