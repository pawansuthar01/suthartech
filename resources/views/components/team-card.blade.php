@props(['member'])

<article class="agency-team-member-card">
    <div class="agency-team-member-avatar" aria-hidden="true">{{ $member['initials'] }}</div>
    <div class="agency-team-member-copy">
        <h3>{{ $member['name'] }}</h3>
        <span>{{ $member['role'] }}</span>
        <p>{{ $member['bio'] }}</p>
    </div>
</article>
