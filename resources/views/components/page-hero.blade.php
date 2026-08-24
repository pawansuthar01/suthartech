@props(['eyebrow', 'title', 'description'])

<section class="agency-page-hero px-6 md:px-16 py-16 md:py-24">
    <div class="agency-page-container max-w-7xl mx-auto">
        <span class="agency-eyebrow">{{ $eyebrow }}</span>
        <h1 class="agency-page-title">{{ $title }}</h1>
        <p class="agency-page-lead">{{ $description }}</p>
        @if ($slot->isNotEmpty())
            <div class="agency-page-actions">{{ $slot }}</div>
        @endif
    </div>
</section>
