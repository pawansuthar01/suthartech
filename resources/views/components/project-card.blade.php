@props(['project'])

<article class="agency-project-card">
    <div class="agency-project-media">
        <img src="{{ asset($project['image']) }}" alt="{{ $project['title'] }} project preview" loading="lazy">
        <span>{{ $project['category'] }}</span>
    </div>
    <div class="agency-project-content">
        <h2>{{ $project['title'] }}</h2>
        <p>{{ $project['description'] }}</p>
        <div class="agency-tag-list" aria-label="Technologies used">
            @foreach ($project['technologies'] as $technology)
                <span>{{ $technology }}</span>
            @endforeach
        </div>
    </div>
</article>
