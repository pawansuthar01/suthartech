@extends('layouts.site')

@section('content')
    <article class="agency-page agency-project-detail blog-detail-page">
        <section class="agency-section px-6 md:px-16">
            <div class="agency-page-container agency-project-detail-container">
                <a class="agency-project-back" href="{{ route('blogs') }}"><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span> All articles</a>
                <header class="blog-detail-heading">
                    <span class="agency-eyebrow">SutharTech journal</span>
                    <h1 class="agency-section-title">{{ $blog->title }}</h1>
                    @if($blog->published_at)<p class="blog-detail-date"><span>Published</span> <time datetime="{{ $blog->published_at->toDateString() }}">{{ $blog->published_at->format('F j, Y') }}</time></p>@endif
                    @if($blog->excerpt)<p class="agency-section-description">{{ $blog->excerpt }}</p>@endif
                </header>

                @if($blog->image_path)
                    <figure class="blog-detail-figure">
                    <img class="agency-project-detail-image blog-detail-image" src="{{ $blog->image_url }}" alt="{{ $blog->image_alt ?: $blog->title }}" fetchpriority="high">
                    </figure>
                @endif

                <div class="agency-project-detail-content blog-detail-content">
                    <div class="agency-content-body">{!! $bodyHtml !!}</div>
                </div>
                <a class="btn-slate-secondary blog-detail-return" href="{{ route('blogs') }}">Explore more articles <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
            </div>
        </section>
    </article>
@endsection
