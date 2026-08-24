<meta name="description" content="{{ $seo['description'] }}">
<meta name="keywords" content="{{ $seo['keywords'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">
<meta name="robots" content="{{ $seo['robots'] }}">

<meta property="og:type" content="{{ $seo['og_type'] }}">
<meta property="og:site_name" content="SutharTech">
<meta property="og:title" content="{{ $seo['og_title'] }}">
<meta property="og:description" content="{{ $seo['og_description'] }}">
<meta property="og:image" content="{{ $seo['og_image'] }}">
<meta property="og:url" content="{{ $seo['og_url'] }}">

<meta name="twitter:card" content="{{ $seo['twitter_card'] }}">
<meta name="twitter:site" content="{{ $seo['twitter_site'] }}">
<meta name="twitter:title" content="{{ $seo['twitter_title'] }}">
<meta name="twitter:description" content="{{ $seo['twitter_description'] }}">
<meta name="twitter:image" content="{{ $seo['twitter_image'] }}">

<script type="application/ld+json">{!! $seo['schema_json'] !!}</script>
