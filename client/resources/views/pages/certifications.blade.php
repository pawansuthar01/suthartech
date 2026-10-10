@extends('layouts.site')

@section('content')
    <div class="agency-page certifications-page">
        <section class="agency-page-hero projects-page-hero certifications-page-hero px-6 md:px-16 py-16 md:py-24">
            <div class="agency-page-container max-w-7xl mx-auto projects-page-hero-grid certifications-hero-layout">
                <div class="projects-page-hero-copy">
                    <span class="agency-eyebrow">SutharTech · public register</span>
                    <h1 class="agency-page-title">Certificates you can verify.</h1>
                    <p class="agency-page-lead">Browse published company certifications or look up a record using the exact ID shown on its issuer documentation. Every public record includes its issuer and available validity details.</p>
                    <div class="credential-register-note">
                        <span class="material-symbols-outlined" aria-hidden="true">fact_check</span>
                        <span>Only records published by the SutharTech team appear in this register.</span>
                    </div>
                </div>
                <aside class="credential-lookup-panel credential-hero-panel">
                    <div class="credential-lookup-heading">
                        <span class="credential-lookup-icon material-symbols-outlined" aria-hidden="true">search_check</span>
                        <span class="agency-eyebrow">Direct lookup</span>
                        <h2 class="agency-section-title">Verify by certificate ID</h2>
                        <p>Enter the ID exactly as it appears on the issuer’s certificate or verification page.</p>
                    </div>
                    <form class="credential-lookup-form" method="GET" action="{{ route('certifications.verify') }}">
                        <label class="sr-only" for="credential-id">Certificate ID</label>
                        <input id="credential-id" name="credential_id" type="text" maxlength="190" value="{{ old('credential_id', $credentialId ?? request('credential_id')) }}" placeholder="e.g. certificate ID" autocomplete="off" required>
                        <button class="btn-slate-primary" type="submit">Search records <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></button>
                        @error('credential_id')<p class="credential-lookup-feedback" role="alert">{{ $message }}</p>@enderror
                        @if(!empty($lookupError))<p class="credential-lookup-feedback" role="status">{{ $lookupError }}</p>@endif
                    </form>
                </aside>
            </div>
        </section>

        <section class="agency-section px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto">
                <div class="agency-section-heading credential-list-heading">
                    <div>
                        <span class="agency-eyebrow">Published records</span>
                        <h2 class="agency-section-title">Company certifications</h2>
                    </div>
                    <span class="credential-result-count">{{ number_format($certifications->total()) }} {{ \Illuminate\Support\Str::plural('record', $certifications->total()) }}</span>
                </div>

                @if($certifications->isNotEmpty())
                    <div class="agency-projects-grid credential-cards-grid">
                        @foreach($certifications as $certification)
                            <article class="agency-project-card credential-card">
                                <a class="credential-card-link" href="{{ route('certification.show', $certification->slug) }}">
                                    <div class="credential-card-mark">
                                        @if($certification->image_path)
                                            <img src="{{ asset('storage/'.$certification->image_path) }}" alt="{{ $certification->title }} certificate" loading="lazy">
                                        @else
                                            <x-certificate-preview :certification="$certification" />
                                        @endif
                                        <span class="credential-mark-caption">SutharTech record</span>
                                    </div>
                                    <div class="agency-project-content">
                                        <span class="credential-card-overline">Issued by</span>
                                        <span class="credential-issuer">{{ $certification->issuer }}</span>
                                        <h3>{{ $certification->title }}</h3>
                                        @if($certification->credential_id)<p class="credential-card-id"><span class="material-symbols-outlined" aria-hidden="true">tag</span><span>{{ $certification->credential_id }}</span></p>@endif
                                        <div class="credential-card-meta">
                                            @if($certification->issued_at)<span><span class="material-symbols-outlined" aria-hidden="true">event</span> Issued {{ $certification->issued_at->format('M Y') }}</span>@endif
                                            @if($certification->expires_at)
                                                <span class="certificate-validity {{ $certification->expires_at->lt(today()) ? 'is-expired' : '' }}"><span class="material-symbols-outlined" aria-hidden="true">{{ $certification->expires_at->lt(today()) ? 'history' : 'verified' }}</span> {{ $certification->expires_at->lt(today()) ? 'Expired' : 'Valid through' }} {{ $certification->expires_at->format('M Y') }}</span>
                                            @endif
                                        </div>
                                        <span class="credential-company-label"><span class="material-symbols-outlined" aria-hidden="true">verified</span> {{ str_starts_with($certification->credential_id ?? '', 'SAMPLE-') ? 'Testing sample · not issued' : 'Published record' }}</span>
                                        <span class="agency-project-card-action">View certificate details <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></span>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                    <x-public-pagination :paginator="$certifications" label="Certificate pages" />
                @else
                    <div class="credential-empty-state">
                        <span class="credential-empty-icon material-symbols-outlined" aria-hidden="true">workspace_premium</span>
                        <div>
                            <h3>No company certifications published yet</h3>
                            <p>When a verified company certification is published by the SutharTech team, its issuer, certificate ID, and available validity details will appear here.</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
