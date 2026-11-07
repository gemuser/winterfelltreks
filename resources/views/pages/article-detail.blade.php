@extends('layouts.app')

@section('content')
<article class="article-detail min-h-screen">
    {{-- Header --}}
    <header class="py-16 lg:py-24 bg-background-alt border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center justify-center space-x-2 text-sm text-text-secondary">
                    <li><a href="{{ url('/') }}" class="hover:text-accent-primary transition-colors">Home</a></li>
                    <li class="text-gray-400">/</li>
                    <li><a href="{{ url('/travel-guide') }}" class="hover:text-accent-primary transition-colors">Travel Guide</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-text-primary font-medium truncate max-w-xs" aria-current="page">{{ $article->title }}</li>
                </ol>
            </nav>

            @if($article->category)
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-accent-primary text-white text-xs font-semibold uppercase tracking-wider mb-4">
                {{ $article->category->name }}
            </div>
            @endif

            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-text-primary tracking-tight leading-tight mb-6">
                {{ $article->title }}
            </h1>

            <div class="flex items-center justify-center gap-4 text-sm text-text-secondary">
                @if($article->author)
                <span class="font-medium text-text-primary">By {{ $article->author }}</span>
                <span>·</span>
                @endif
                @if($article->published_at)
                <time datetime="{{ $article->published_at->format('c') }}">
                    {{ $article->published_at->format('F d, Y') }}
                </time>
                @endif
            </div>
        </div>
    </header>

    {{-- Cover Image --}}
    @if($article->cover_image)
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 sm:-mt-12">
        <div class="aspect-[16/9] overflow-hidden shadow-lg border border-gray-200">
            <img src="{{ $article->cover_image }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
        </div>
    </div>
    @endif

    {{-- Content --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($article->excerpt)
            <p class="text-xl text-text-secondary leading-relaxed font-serif italic mb-8 pb-8 border-b border-gray-200">
                {{ strip_tags($article->excerpt) }}
            </p>
            @endif

            <div class="prose prose-lg max-w-none text-text-secondary leading-relaxed">
                {!! $article->content !!}
            </div>

            {{-- Back to Guide & CTA --}}
            <div class="mt-16 pt-8 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ url('/travel-guide') }}" class="inline-flex items-center gap-2 text-accent-primary font-medium hover:gap-3 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path></svg>
                    Back to Travel Guide
                </a>

                <a href="{{ url('/enquiry') }}" class="btn-primary px-6 py-3 text-sm font-semibold">
                    Plan Your Expedition
                </a>
            </div>
        </div>
    </section>

    {{-- Related Articles --}}
    @if($relatedArticles->isNotEmpty())
    <section class="py-16 bg-background-alt border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-text-primary mb-8 text-center">Related Articles</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                @foreach($relatedArticles as $rel)
                <article class="bg-white border border-gray-200 overflow-hidden shadow-sm flex flex-col justify-between">
                    <div>
                        @if($rel->cover_image)
                        <div class="aspect-[16/10] overflow-hidden">
                            <img src="{{ $rel->cover_image }}" alt="{{ $rel->title }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="p-6">
                            <h3 class="font-display text-lg font-bold text-text-primary mb-2">
                                <a href="{{ url('/travel-guide/' . $rel->slug) }}" class="hover:text-accent-primary transition-colors">
                                    {{ $rel->title }}
                                </a>
                            </h3>
                            @if($rel->excerpt)
                            <p class="text-text-secondary text-xs leading-relaxed">{{ Str::limit(strip_tags($rel->excerpt), 90) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ url('/travel-guide/' . $rel->slug) }}" class="text-xs text-accent-primary font-medium">Read More &rarr;</a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</article>
@endsection

