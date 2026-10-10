@php($isSample = str_starts_with($certification->credential_id ?? '', 'SAMPLE-'))
@php($titleSize = mb_strlen($certification->title) > 42 ? 26 : (mb_strlen($certification->title) > 30 ? 32 : 38))

<svg class="certificate-preview" viewBox="0 0 1200 760" role="img" aria-label="Certificate record preview for {{ $certification->title }}">
    <rect width="1200" height="760" fill="#fffefa" />
    <rect x="22" y="22" width="1156" height="716" rx="4" fill="none" stroke="#cbd5e1" stroke-width="2" />
    <rect x="38" y="38" width="1124" height="684" rx="2" fill="none" stroke="#e2e8f0" stroke-width="1" />
    <path d="M38 165h1124M38 620h1124" stroke="#e2e8f0" stroke-width="1" />
    <rect x="82" y="75" width="54" height="54" rx="12" fill="#f4c7d7" />
    <text x="109" y="112" fill="#0f172a" font-family="Arial, sans-serif" font-size="26" font-weight="700" text-anchor="middle">S</text>
    <text x="155" y="98" fill="#0f172a" font-family="Arial, sans-serif" font-size="20" font-weight="700" letter-spacing="1.4">SUTHARTECH</text>
    <text x="155" y="126" fill="#64748b" font-family="Arial, sans-serif" font-size="13" letter-spacing="2">PUBLIC CERTIFICATE REGISTER</text>
    <circle cx="1080" cy="104" r="33" fill="#f8fafc" stroke="#cbd5e1" stroke-width="2" />
    <path d="M1069 104l8 8 15-18" fill="none" stroke="#0f172a" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" />
    <text x="600" y="230" fill="#64748b" font-family="Arial, sans-serif" font-size="16" font-weight="700" letter-spacing="4" text-anchor="middle">CERTIFICATE RECORD</text>
    <text x="600" y="302" fill="#0f172a" font-family="Arial, sans-serif" font-size="{{ $titleSize }}" font-weight="700" text-anchor="middle">{{ $certification->title }}</text>
    <text x="600" y="356" fill="#64748b" font-family="Arial, sans-serif" font-size="17" text-anchor="middle">Issued by</text>
    <text x="600" y="394" fill="#0f172a" font-family="Arial, sans-serif" font-size="24" font-weight="600" text-anchor="middle">{{ $certification->issuer }}</text>
    <rect x="92" y="445" width="1016" height="120" rx="6" fill="#f8fafc" stroke="#e2e8f0" />
    <text x="128" y="485" fill="#64748b" font-family="Arial, sans-serif" font-size="13" font-weight="700" letter-spacing="1.2">CERTIFICATE ID</text>
    <text x="128" y="526" fill="#0f172a" font-family="Arial, sans-serif" font-size="21" font-weight="600">{{ $certification->credential_id ?: 'Not provided' }}</text>
    <text x="682" y="485" fill="#64748b" font-family="Arial, sans-serif" font-size="13" font-weight="700" letter-spacing="1.2">ISSUE DATE</text>
    <text x="682" y="526" fill="#0f172a" font-family="Arial, sans-serif" font-size="21" font-weight="600">{{ $certification->issued_at?->format('F j, Y') ?: 'Not provided' }}</text>
    @if($certification->expires_at)
        <text x="682" y="550" fill="#64748b" font-family="Arial, sans-serif" font-size="13">Valid through {{ $certification->expires_at->format('F j, Y') }}</text>
    @endif
    <text x="600" y="670" fill="#64748b" font-family="Arial, sans-serif" font-size="13" letter-spacing="1.3" text-anchor="middle">{{ $isSample ? 'SAMPLE FOR TESTING · NOT AN ISSUED CERTIFICATE' : 'RECORD PREVIEW · VERIFY WITH THE ISSUER' }}</text>
    @if($isSample)
        <g transform="rotate(-18 600 380)" opacity=".13">
            <text x="600" y="405" fill="#be123c" font-family="Arial, sans-serif" font-size="70" font-weight="700" letter-spacing="8" text-anchor="middle">SAMPLE</text>
        </g>
    @endif
</svg>
