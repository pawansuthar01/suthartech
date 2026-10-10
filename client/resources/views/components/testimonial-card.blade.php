@props(['testimonial'])

<article class="agency-testimonial-card">
    <div class="agency-testimonial-stars" aria-label="5 out of 5 stars">★★★★★</div>
    <blockquote>“{{ $testimonial['quote'] }}”</blockquote>
    <footer>
        <strong>{{ $testimonial['name'] }}</strong>
        <span>{{ $testimonial['role'] }}</span>
    </footer>
</article>
