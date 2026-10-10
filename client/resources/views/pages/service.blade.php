@extends('layouts.site')
@section('content')
    <article class="agency-page"><section class="agency-section px-6 md:px-16"><div class="agency-page-container max-w-4xl mx-auto">
        <a class="agency-eyebrow" href="{{ route('services') }}">Services</a><h1 class="agency-section-title">{{ $service->title }}</h1><p class="agency-section-description">{{ $service->description }}</p>
        @if($service->body)<div class="agency-content-body">{{ $service->body }}</div>@endif
        @if($service->deliverables)<ul class="agency-card-list">@foreach($service->deliverables as $deliverable)<li><span class="material-symbols-outlined" aria-hidden="true">check</span>{{ $deliverable }}</li>@endforeach</ul>@endif
        <p><a class="btn-slate-primary" href="{{ route('contact') }}">Contact</a></p>
    </div></section></article>
@endsection
