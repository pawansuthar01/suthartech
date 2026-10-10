@extends('layouts.admin')
@section('title', ($item->exists ? 'Edit ' : 'Add ').$label)
@section('content')
    <div class="admin-page-heading"><div><h2>{{ $item->exists ? 'Edit' : 'Add' }} {{ $label }}</h2><p>Drafts remain hidden from public pages until published.</p></div><a class="admin-button" href="{{ route('admin.'.$module.'.index') }}">Back to {{ strtolower($label) }}</a></div>
    <form class="admin-panel" method="POST" action="{{ $item->exists ? route('admin.'.$module.'.update', $item) : route('admin.'.$module.'.store') }}" enctype="multipart/form-data">
        @csrf @if($item->exists) @method('PUT') @endif
        <div style="padding:22px">@include('admin.content.fields.'.$module)
            @if(in_array($module, ['blogs', 'projects', 'services', 'pages', 'certifications'], true))
                <details style="margin:8px 0 22px"><summary style="cursor:pointer;font-weight:650">Search engine metadata</summary><div class="admin-grid-2" style="margin-top:16px"><div class="admin-field"><label for="meta_title">Meta title</label><input id="meta_title" name="meta_title" maxlength="255" value="{{ old('meta_title', $item->meta_title) }}"></div><div class="admin-field"><label for="meta_description">Meta description</label><textarea id="meta_description" name="meta_description" maxlength="320" rows="2">{{ old('meta_description', $item->meta_description) }}</textarea></div></div></details>
            @endif
            <div class="admin-field"><label for="status">Publishing status</label><select id="status" name="status" required>
                @if($module === 'certifications')
                    <option value="draft" @selected(old('status', $item->status) === 'draft')>Draft — hidden publicly</option>
                    <option value="active" @selected(old('status', $item->status) === 'active')>Active — visible publicly</option>
                    <option value="inactive" @selected(old('status', $item->status) === 'inactive')>Inactive — hidden publicly</option>
                @else
                    <option value="draft" @selected(old('status', $item->status) === 'draft')>Draft — hidden publicly</option>
                    <option value="published" @selected(old('status', $item->status) === 'published')>Published — visible publicly</option>
                @endif
            </select></div>
            @if($module === 'projects')<div class="admin-field"><small>Published projects appear on the Projects page. Featured projects also appear in the homepage carousel.</small></div>@endif
            @if(in_array($module, ['projects', 'certifications', 'pages', 'services', 'feedback'], true))<div class="admin-field"><label for="sort_order">Display order</label><input id="sort_order" name="sort_order" type="number" min="0" max="10000" value="{{ old('sort_order', $item->sort_order ?? 0) }}"></div>@endif
            <button class="admin-button admin-button-primary" type="submit">{{ $item->exists ? 'Save changes' : 'Save item' }}</button>
        </div>
    </form>
@endsection
