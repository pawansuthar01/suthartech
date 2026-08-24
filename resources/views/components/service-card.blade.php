@props(['service'])

<article class="agency-service-card">
    <div class="agency-card-icon {{ $service['accent'] ?? 'agency-icon-blue' }}">
        <span class="material-symbols-outlined" aria-hidden="true">{{ $service['icon'] }}</span>
    </div>
    <h2>{{ $service['title'] }}</h2>
    <p>{{ $service['description'] }}</p>
    @if (!empty($service['deliverables']))
        <ul class="agency-card-list">
            @foreach ($service['deliverables'] as $deliverable)
                <li><span class="material-symbols-outlined" aria-hidden="true">check</span>{{ $deliverable }}</li>
            @endforeach
        </ul>
    @endif
</article>
