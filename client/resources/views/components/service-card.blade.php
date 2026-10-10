@props(['service', 'number' => null])

<article class="agency-service-card">
    <div class="agency-service-card-top">
        <div class="agency-card-icon {{ $service['accent'] ?? 'agency-icon-blue' }}">
            <span class="material-symbols-outlined" aria-hidden="true">{{ $service['icon'] }}</span>
        </div>
        @if ($number)<span class="agency-service-number">{{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span>@endif
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
