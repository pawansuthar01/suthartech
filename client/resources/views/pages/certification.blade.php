@extends('layouts.site')

@section('content')
    <article class="agency-page credential-detail-page">
        <section class="agency-section px-6 md:px-16">
            <div class="agency-page-container agency-project-detail-container">
                <a class="agency-project-back" href="{{ route('certifications') }}"><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span> All certificates</a>
                <div class="credential-detail-card">
                    <div class="credential-detail-heading">
                        <span class="agency-eyebrow">{{ str_starts_with($certification->credential_id ?? '', 'SAMPLE-') ? 'Sample certificate · demo only' : 'Company certificate' }}</span>
                        <h1 class="agency-section-title">{{ $certification->title }}</h1>
                        <p>{{ $certification->issuer }}</p>
                    </div>
                    @if($certification->image_path)
                        <img class="credential-detail-image" src="{{ asset('storage/'.$certification->image_path) }}" alt="{{ $certification->title }} certificate" fetchpriority="high">
                    @else
                        <div class="credential-detail-preview"><x-certificate-preview :certification="$certification" /></div>
                    @endif
                    <dl class="credential-facts">
                        @if($certification->credential_id)<div><dt>Certificate ID</dt><dd>{{ $certification->credential_id }}</dd></div>@endif
                        @if($certification->issued_at)<div><dt>Issue date</dt><dd>{{ $certification->issued_at->format('F j, Y') }}</dd></div>@endif
                        @if($certification->expires_at)
                            <div><dt>Validity</dt><dd class="{{ $certification->expires_at->lt(today()) ? 'certificate-validity-text-expired' : '' }}">{{ $certification->expires_at->lt(today()) ? 'Expired' : 'Valid through' }} {{ $certification->expires_at->format('F j, Y') }}</dd></div>
                        @endif
                    </dl>
                    @if($certification->description)<div class="agency-content-body credential-detail-description">{{ $certification->description }}</div>@endif
                    @if($certification->credential_url)
                        <a class="btn-slate-primary credential-verify-link" href="{{ $certification->credential_url }}" target="_blank" rel="noopener noreferrer">Verify with issuer <span class="material-symbols-outlined" aria-hidden="true">open_in_new</span></a>
                    @endif
                </div>
            </div>
        </section>
    </article>
@endsection
