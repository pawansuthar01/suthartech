@extends('layouts.site')

@section('content')
    @php
        $projectImage = !$project->image_path
            ? null
            : (str_starts_with($project->image_path, 'images/') ? asset($project->image_path) : asset('storage/'.$project->image_path));
    @endphp
    <article class="agency-page agency-project-detail">
        <section class="agency-section px-6 md:px-16">
            <div class="agency-page-container agency-project-detail-container">
                <a class="agency-project-back" href="{{ route('projects') }}"><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span> All projects</a>
                <div class="agency-project-detail-heading">
                    <span class="agency-eyebrow">{{ $project->category }}</span>
                    <h1 class="agency-section-title">{{ $project->title }}</h1>
                    @if($project->summary)<p class="agency-section-description">{{ $project->summary }}</p>@endif
                    @if($project->project_url)
                        <a class="btn-slate-primary" href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer">Visit project <span class="material-symbols-outlined" aria-hidden="true">open_in_new</span></a>
                    @endif
                </div>

                @if($projectImage)
                    <img class="agency-project-detail-image" src="{{ $projectImage }}" alt="{{ $project->title }} project" fetchpriority="high">
                @endif

                <div class="agency-project-detail-content">
                    @if($project->body)
                        <section class="agency-project-detail-section">
                            <h2>About this project</h2>
                            <div class="agency-content-body">{!! nl2br(e($project->body)) !!}</div>
                        </section>
                    @endif
                    @if($project->technologies && count($project->technologies))
                        <section class="agency-project-detail-section">
                            <h2>Technology</h2>
                            <div class="agency-tag-list" aria-label="Technologies used">
                                @foreach($project->technologies as $technology)<span>{{ $technology }}</span>@endforeach
                            </div>
                        </section>
                    @endif
                </div>
            </div>
        </section>
    </article>
@endsection
