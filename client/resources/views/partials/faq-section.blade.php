<section class="home-faq-section max-w-7xl mx-auto px-6 md:px-16 py-14 md:py-20" aria-labelledby="{{ $id }}">
    <div class="home-faq-heading">
        <span class="home-faq-eyebrow">FAQs</span>
        <h2 id="{{ $id }}">{{ $title }}</h2>
        <p>{{ $description }}</p>
    </div>
    <div class="home-faq-list">
        @foreach ($faqs as $faq)
            <details class="home-faq-item">
                <summary>{{ $faq->question }}<span class="material-symbols-outlined" aria-hidden="true">add</span></summary>
                <div class="home-faq-answer">{{ $faq->answer }}</div>
            </details>
        @endforeach
    </div>
</section>
