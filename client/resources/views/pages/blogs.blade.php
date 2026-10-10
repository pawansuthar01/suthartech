@extends('layouts.site')

@section('content')
    <div class="blogs-page">
        <div class="blog-index-shell">
            <aside class="blog-index-sidebar" aria-label="Blog navigation">
                <a class="blog-sidebar-all {{ $activeTopic ? '' : 'is-active' }}" href="{{ route('blogs') }}">All posts</a>

                <section class="blog-sidebar-group" aria-labelledby="blog-recent-heading">
                    <h2 id="blog-recent-heading">Recent</h2>
                    <ul>
                        @foreach($recentBlogs as $recentBlog)
                            <li><a href="{{ route('blog.show', $recentBlog->slug) }}">{{ $recentBlog->title }}</a></li>
                        @endforeach
                    </ul>
                </section>

                @if($topics->isNotEmpty())
                    <section class="blog-sidebar-group" aria-labelledby="blog-topics-heading">
                        <h2 id="blog-topics-heading">Topics</h2>
                        <ul>
                            @foreach($topics as $topic)
                                <li>
                                    <a href="{{ route('blogs', ['topic' => $topic->topic]) }}" @if($activeTopic === $topic->topic) aria-current="page" @endif>
                                        {{ $topic->topic_label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </aside>

            <details class="blog-mobile-navigation">
                <summary>Browse blog</summary>
                <div class="blog-mobile-navigation-content">
                    <a href="{{ route('blogs') }}">All posts</a>
                    <div>
                        <h2>Recent</h2>
                        @foreach($recentBlogs as $recentBlog)
                            <a href="{{ route('blog.show', $recentBlog->slug) }}">{{ $recentBlog->title }}</a>
                        @endforeach
                    </div>
                    @if($topics->isNotEmpty())
                        <div>
                            <h2>Topics</h2>
                            @foreach($topics as $topic)
                                <a href="{{ route('blogs', ['topic' => $topic->topic]) }}" @if($activeTopic === $topic->topic) aria-current="page" @endif>{{ $topic->topic_label }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </details>

            <div class="blog-index-main">
                <header class="blog-index-hero">
                    <h1>SutharTech Blog</h1>
                    <p>Insights on product engineering and building dependable digital experiences.</p>
                </header>

                @if($blogs->isNotEmpty())
                    <div class="blog-index-feed" aria-label="Blog posts">
                        @foreach($blogs as $blog)
                            <article class="blog-index-story {{ $blogs->currentPage() === 1 && $loop->first ? 'is-featured' : '' }}">
                                @if($blog->image_path)
                                    <a class="blog-index-story-image" href="{{ route('blog.show', $blog->slug) }}" tabindex="-1" aria-hidden="true">
                                        <img src="{{ $blog->image_url }}" alt="" @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                                    </a>
                                @endif
                                <div class="blog-index-story-copy">
                                    <div class="blog-index-story-meta">
                                        @if($blog->published_at)<time datetime="{{ $blog->published_at->toDateString() }}">{{ $blog->published_at->format('M j') }}</time>@endif
                                        @if($blog->topic)<span>{{ $blog->topic_label }}</span>@endif
                                    </div>
                                    <h2><a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a></h2>
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($blog->excerpt ?: $blog->body), 220) }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <x-public-pagination :paginator="$blogs" label="Blog pages" />
                @else
                    <div class="agency-empty">No articles published yet.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
