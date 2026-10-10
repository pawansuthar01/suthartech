@extends('layouts.site')
@section('content')
    <article class="agency-page">
        <x-page-hero :eyebrow="$page->hero_eyebrow ?: $page->title" :title="$page->hero_title ?: $page->title" :description="$page->hero_description ?: ''" />
        @if($page->body)<section class="agency-section px-6 md:px-16"><div class="agency-page-container max-w-4xl mx-auto agency-content-body">{{ $page->body }}</div></section>@endif
    </article>
@endsection
