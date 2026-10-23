@extends('front.layouts.app')

@php
    $plain = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($articleNews->content))));
    $desc  = \Illuminate\Support\Str::limit($plain, 160);
    $image = asset('storage/' . $articleNews->thumbnail);
@endphp

@section('description', $desc)

@push('meta')
    <link rel="canonical" href="{{ route('front.details', $articleNews->slug) }}" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ $articleNews->name }}" />
    <meta property="og:description" content="{{ $desc }}" />
    <meta property="og:image" content="{{ $image }}" />
    <meta property="og:url" content="{{ route('front.details', $articleNews->slug) }}" />
    <meta property="article:published_time" content="{{ $articleNews->created_at->toIso8601String() }}" />
    <meta name="twitter:card" content="summary_large_image" />
@endpush

@section('title', $articleNews->name . ' - Nalarakyat')

@section('content')

{{-- ===================== HEADER ARTIKEL ===================== --}}
<header class="flex flex-col items-center gap-[50px] mt-[70px]">
    <div id="Headline" class="max-w-[1130px] mx-auto flex flex-col gap-4 items-center">
        <p class="w-fit text-[#A3A6AE]">
            {{ $articleNews->created_at->translatedFormat('d M Y') }}
            @if ($articleNews->category)
                • <a href="{{ route('front.category', $articleNews->category->slug) }}" class="hover:underline">{{ $articleNews->category->name }}</a>
            @endif
        </p>
        <h1 id="Title" class="font-bold text-[46px] leading-[60px] text-center two-lines">{{ $articleNews->name }}</h1>
        @if ($articleNews->author)
        <a id="Author" href="{{ route('front.author', $articleNews->author->slug) }}" class="w-fit h-fit">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full overflow-hidden">
                    <img src="{{ asset('storage/' . $articleNews->author->avatar) }}" class="object-cover w-full h-full" alt="{{ $articleNews->author->name }}">
                </div>
                <div class="flex flex-col">
                    <p class="font-semibold text-sm leading-[21px]">{{ $articleNews->author->name }}</p>
                    <p class="text-xs leading-[18px] text-[#A3A6AE]">{{ $articleNews->author->occupation }}</p>
                </div>
            </div>
        </a>
        @endif
    </div>
    <div class="w-full h-[500px] flex shrink-0 overflow-hidden">
        <img src="{{ asset('storage/' . $articleNews->thumbnail) }}" class="object-cover w-full h-full" alt="{{ $articleNews->name }}">
    </div>
</header>

{{-- ===================== ISI + SIDEBAR ===================== --}}
<section id="Article-container" class="max-w-[1130px] mx-auto flex gap-20 mt-[50px]">
    <div class="flex-1 min-w-0">
        <article id="Content-wrapper">
            {!! $content !!}
        </article>

        {{-- Kotak penulis --}}
        @if ($articleNews->author)
        <a href="{{ route('front.author', $articleNews->author->slug) }}" class="block" style="margin-top:48px">
            <div class="rounded-[20px] ring-1 ring-[#EEF0F7] hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300"
                style="display:flex;align-items:center;gap:16px;padding:20px 24px">
                <div class="shrink-0 rounded-full overflow-hidden" style="width:70px;height:70px">
                    <img src="{{ asset('storage/' . $articleNews->author->avatar) }}" class="object-cover w-full h-full" alt="{{ $articleNews->author->name }}">
                </div>
                <div style="display:flex;flex-direction:column;gap:4px">
                    <p style="font-size:12px;color:#A3A6AE">Ditulis oleh</p>
                    <p class="font-bold" style="font-size:18px">{{ $articleNews->author->name }}</p>
                    <p style="font-size:14px;color:#A3A6AE">{{ $articleNews->author->occupation }}</p>
                </div>
            </div>
        </a>
        @endif
    </div>

    <div class="side-bar flex flex-col w-[300px] shrink-0 gap-10">
        @php($sidebarBanners = ($banners['sidebar'] ?? collect())->shuffle()->values())

        @if ($sidebarBanners->count() > 0)
        <div class="ads flex flex-col gap-3 w-full">
            <a href="{{ $sidebarBanners[0]->link }}" target="_blank" rel="noopener sponsored">
                <img src="{{ asset('storage/' . $sidebarBanners[0]->thumbnail) }}" class="object-contain w-full h-full" alt="Iklan" />
            </a>
            <p class="font-medium text-sm leading-[21px] text-[#A3A6AE]">Iklan</p>
        </div>
        @endif

        @if ($moreFromAuthor->isNotEmpty())
        <div id="More-from-author" class="flex flex-col gap-4">
            <p class="font-bold">Tulisan Lain dari Penulis</p>
            @foreach ($moreFromAuthor as $item)
            <a href="{{ route('front.details', $item->slug) }}" class="card-from-author">
                <div class="rounded-[20px] ring-1 ring-[#EEF0F7] p-[14px] flex gap-4 hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300">
                    <div class="w-[70px] h-[70px] flex shrink-0 overflow-hidden rounded-2xl">
                        <img src="{{ asset('storage/' . $item->thumbnail) }}" class="object-cover w-full h-full" alt="{{ $item->name }}">
                    </div>
                    <div class="flex flex-col gap-[6px]">
                        <p class="line-clamp-2 font-bold">{{ $item->name }}</p>
                        <p class="text-xs leading-[18px] text-[#A3A6AE]">{{ $item->created_at->translatedFormat('d M Y') }} • {{ $item->category?->name }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        @if ($sidebarBanners->count() > 1)
        <div class="ads flex flex-col gap-3 w-full">
            <a href="{{ $sidebarBanners[1]->link }}" target="_blank" rel="noopener sponsored">
                <img src="{{ asset('storage/' . $sidebarBanners[1]->thumbnail) }}" class="object-contain w-full h-full" alt="Iklan" />
            </a>
            <p class="font-medium text-sm leading-[21px] text-[#A3A6AE]">Iklan</p>
        </div>
        @endif
    </div>
</section>

{{-- ===================== IKLAN LEBAR (posisi: Footer) ===================== --}}
@if (!empty($banners['footer']) && $banners['footer']->isNotEmpty())
@php($wide = $banners['footer']->random())
<section id="Advertisement" class="max-w-[1130px] mx-auto flex justify-center mt-[70px]">
    <div class="flex flex-col gap-3 shrink-0 w-fit">
        <a href="{{ $wide->link }}" target="_blank" rel="noopener sponsored">
            <div class="w-[900px] h-[120px] flex shrink-0 border border-[#EEF0F7] rounded-2xl overflow-hidden">
                <img src="{{ asset('storage/' . $wide->thumbnail) }}" class="object-cover w-full h-full" alt="Iklan" />
            </div>
        </a>
        <p class="font-medium text-sm leading-[21px] text-[#A3A6AE]">Iklan</p>
    </div>
</section>
@endif

{{-- ===================== ARTIKEL TERKAIT ===================== --}}
@if ($related->isNotEmpty())
<section id="Up-to-date" class="w-full flex justify-center mt-[70px] py-[50px] bg-[#F9F9FC]">
    <div class="max-w-[1130px] mx-auto flex flex-col gap-[30px]">
        <h2 class="font-bold text-[26px] leading-[39px]">
            Berita Lain yang <br />
            Mungkin Kamu Suka
        </h2>
        <div class="grid grid-cols-3 gap-[30px]">
            @foreach ($related as $item)
            <a href="{{ route('front.details', $item->slug) }}" class="card-news">
                <div class="rounded-[20px] ring-1 ring-[#EEF0F7] p-[26px_20px] flex flex-col gap-4 hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300 bg-white">
                    <div class="thumbnail-container w-full h-[200px] rounded-[20px] flex shrink-0 overflow-hidden relative">
                        <p class="badge-white absolute top-5 left-5 rounded-full p-[8px_18px] bg-white font-bold text-xs leading-[18px]">{{ strtoupper($item->category?->name ?? '') }}</p>
                        <img src="{{ asset('storage/' . $item->thumbnail) }}" class="object-cover w-full h-full" alt="{{ $item->name }}" />
                    </div>
                    <div class="card-info flex flex-col gap-[6px]">
                        <h3 class="font-bold text-lg leading-[27px]">{{ $item->name }}</h3>
                        <p class="text-sm leading-[21px] text-[#A3A6AE]">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
