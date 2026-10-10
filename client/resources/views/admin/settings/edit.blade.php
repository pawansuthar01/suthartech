@extends('layouts.admin')
@section('title', 'SEO & settings')
@section('content')
    <div class="admin-page-heading"><div><h2>SEO & website settings</h2><p>Set accurate public business details and site-wide search metadata.</p></div></div>
    <form class="admin-panel" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">@csrf @method('PUT')
        <div style="padding:22px">
            <div class="admin-grid-2"><div class="admin-field"><label for="site_name">Website name</label><input id="site_name" name="site_name" maxlength="120" required value="{{ old('site_name', $settings->site_name ?: 'SutharTech') }}"></div><div class="admin-field"><label for="contact_email">Public contact email</label><input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings->contact_email) }}"></div></div>
            <div class="admin-grid-2"><div class="admin-field"><label for="contact_phone">Public contact phone</label><input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone) }}"></div><div class="admin-field"><label for="location">Public location</label><input id="location" name="location" value="{{ old('location', $settings->location) }}"></div></div>
            <div class="admin-field"><label for="default_meta_title">Default SEO title</label><input id="default_meta_title" name="default_meta_title" maxlength="255" value="{{ old('default_meta_title', $settings->default_meta_title) }}"></div>
            <div class="admin-field"><label for="default_meta_description">Default SEO description</label><textarea id="default_meta_description" name="default_meta_description" maxlength="320" rows="3">{{ old('default_meta_description', $settings->default_meta_description) }}</textarea></div>
            <div class="admin-field"><label for="default_og_image">Default social share image</label><input id="default_og_image" name="default_og_image" type="file" accept="image/jpeg,image/png,image/webp">@if($settings->default_og_image)<small>Current image: {{ basename($settings->default_og_image) }}</small>@endif</div>
            <h3>Social profiles</h3><div class="admin-grid-2">
                @foreach (['linkedin' => 'LinkedIn', 'github' => 'GitHub', 'x' => 'X'] as $key => $label)<div class="admin-field"><label for="social_{{ $key }}">{{ $label }} URL</label><input id="social_{{ $key }}" name="social_links[{{ $key }}]" type="url" value="{{ old('social_links.'.$key, $settings->social_links[$key] ?? '') }}"></div>@endforeach
            </div>
            <button class="admin-button admin-button-primary" type="submit">Save settings</button>
        </div>
    </form>
@endsection
