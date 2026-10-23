@extends('front.layouts.app')

@section('title', 'Nalarakyat - Portal Berita')

@section('content')

{{-- ===================== ARTIKEL UNGGULAN (CAROUSEL) ===================== --}}
@if ($featured->isNotEmpty())
<section id="Featured" class="mt-[30px]">
    <div class="main-carousel w-full">
        @foreach ($featured as $article)
        <div class="featured-news-card relative w-full h-[550px] flex shrink-0 overflow-hidden">
            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="thumbnail absolute w-full h-full object-cover" alt="{{ $article->name }}" />
            <div class="w-full h-full bg-gradient-to-b from-[rgba(0,0,0,0)] to-[rgba(0,0,0,0.9)] absolute z-10"></div>
            <div class="card-detail max-w-[1130px] w-full mx-auto flex items-end justify-between pb-10 relative z-20">
                <div class="flex flex-col gap-[10px]">
                    <p class="text-white">Unggulan</p>
                    <a href="{{ route('front.details', $article->slug) }}" class="font-bold text-4xl leading-[45px] text-white two-lines hover:underline transition-all duration-300">{{ $article->name }}</a>
                    <p class="text-white">{{ $article->created_at->translatedFormat('d M Y') }} • {{ $article->category?->name }}</p>
                </div>
                <div class="prevNextButtons flex items-center gap-4 mb-[60px]">
                    <button class="button--previous appearance-none w-[38px] h-[38px] flex items-center justify-center rounded-full shrink-0 ring-1 ring-white hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300">
                        <img src="{{ asset('front/assets/images/icons/arrow.svg') }}" alt="sebelumnya" />
                    </button>
                    <button class="button--next appearance-none w-[38px] h-[38px] flex items-center justify-center rounded-full shrink-0 ring-1 ring-white hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300 rotate-180">
                        <img src="{{ asset('front/assets/images/icons/arrow.svg') }}" alt="berikutnya" />
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===================== BERITA TERBARU ===================== --}}
<section id="Up-to-date" class="max-w-[1130px] mx-auto flex flex-col gap-[30px] mt-[70px]">
    <div class="flex justify-between items-center">
        <h2 class="font-bold text-[26px] leading-[39px]">
            Berita Terbaru <br />
            Penuh Rasa Penasaran
        </h2>
        <p class="badge-orange rounded-full p-[8px_18px] bg-[#FFECE1] font-bold text-sm leading-[21px] text-[#FF6B18] w-fit">TERKINI</p>
    </div>
    <div class="grid grid-cols-3 gap-[30px]">
        @foreach ($latest as $article)
        <a href="{{ route('front.details', $article->slug) }}" class="card-news">
            <div class="rounded-[20px] ring-1 ring-[#EEF0F7] p-[26px_20px] flex flex-col gap-4 hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300 bg-white">
                <div class="thumbnail-container w-full h-[200px] rounded-[20px] flex shrink-0 overflow-hidden relative">
                    <p class="badge-white absolute top-5 left-5 rounded-full p-[8px_18px] bg-white font-bold text-xs leading-[18px]">{{ strtoupper($article->category?->name ?? '') }}</p>
                    <img src="{{ asset('storage/' . $article->thumbnail) }}" class="object-cover w-full h-full" alt="{{ $article->name }}" />
                </div>
                <div class="card-info flex flex-col gap-[6px]">
                    <h3 class="font-bold text-lg leading-[27px]">{{ $article->name }}</h3>
                    <p class="text-sm leading-[21px] text-[#A3A6AE]">{{ $article->created_at->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- ===================== PENULIS ===================== --}}
@if ($authors->isNotEmpty())
<section id="Best-authors" class="max-w-[1130px] mx-auto flex flex-col gap-[30px] mt-[70px]">
    <div class="flex flex-col text-center gap-[14px] items-center">
        <p class="badge-orange rounded-full p-[8px_18px] bg-[#FFECE1] font-bold text-sm leading-[21px] text-[#FF6B18] w-fit">PENULIS</p>
        <h2 class="font-bold text-[26px] leading-[39px]">
            Jelajahi Karya <br />
            Para Penulis Kami
        </h2>
    </div>
    <div class="grid grid-cols-6 gap-[30px]">
        @foreach ($authors as $author)
        <a href="{{ route('front.author', $author->slug) }}" class="card-authors">
            <div class="rounded-[20px] border border-[#EEF0F7] p-[26px_20px] flex flex-col items-center gap-4 hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300">
                <div class="w-[70px] h-[70px] flex shrink-0 rounded-full overflow-hidden">
                    <img src="{{ asset('storage/' . $author->avatar) }}" class="object-cover w-full h-full" alt="{{ $author->name }}" />
                </div>
                <div class="flex flex-col gap-1 text-center">
                    <p class="font-semibold">{{ $author->name }}</p>
                    <p class="text-sm leading-[21px] text-[#A3A6AE]">{{ $author->occupation }}</p>
                    <p class="text-xs text-[#A3A6AE]">{{ number_format($author->news_count) }} Berita</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ===================== IKLAN BANNER (HEADER) ===================== --}}
@if (!empty($banners['header']) && $banners['header']->isNotEmpty())
@php($banner = $banners['header']->random())
<section id="Advertisement" class="max-w-[1130px] mx-auto flex justify-center mt-[70px]">
    <div class="flex flex-col gap-3 shrink-0 w-fit">
        <a href="{{ $banner->link }}" target="_blank" rel="noopener sponsored">
            <div class="w-[900px] h-[120px] flex shrink-0 border border-[#EEF0F7] rounded-2xl overflow-hidden">
                <img src="{{ asset('storage/' . $banner->thumbnail) }}" class="object-cover w-full h-full" alt="Iklan" />
            </div>
        </a>
        <p class="font-medium text-sm leading-[21px] text-[#A3A6AE]">Iklan</p>
    </div>
</section>
@endif

{{-- ===================== SECTION PER KATEGORI ===================== --}}
@foreach ($categorySections as $category)
@php($first = $category->news->first())
<section id="Latest-{{ $category->slug }}" class="max-w-[1130px] mx-auto flex flex-col gap-[30px] mt-[70px]">
    <div class="flex justify-between items-center">
        <h2 class="font-bold text-[26px] leading-[39px]">
            Terbaru Untukmu <br />
            di {{ $category->name }}
        </h2>
        <a href="{{ route('front.category', $category->slug) }}" class="rounded-full p-[12px_22px] flex gap-[10px] font-semibold transition-all duration-300 border border-[#EEF0F7] hover:ring-2 hover:ring-[#FF6B18]">Lihat Semua</a>
    </div>
    <div class="flex justify-between items-center h-fit">
        <div class="featured-news-card relative w-full h-[424px] flex flex-1 rounded-[20px] overflow-hidden">
            <img src="{{ asset('storage/' . $first->thumbnail) }}" class="thumbnail absolute w-full h-full object-cover" alt="{{ $first->name }}" />
            <div class="w-full h-full bg-gradient-to-b from-[rgba(0,0,0,0)] to-[rgba(0,0,0,0.9)] absolute z-10"></div>
            <div class="card-detail w-full flex items-end p-[30px] relative z-20">
                <div class="flex flex-col gap-[10px]">
                    <p class="text-white">Terbaru</p>
                    <a href="{{ route('front.details', $first->slug) }}" class="font-bold text-[30px] leading-[36px] text-white hover:underline transition-all duration-300">{{ $first->name }}</a>
                    <p class="text-white">{{ $first->created_at->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </div>

        @if ($category->news->count() > 1)
        <div class="h-[424px] w-fit px-5 overflow-y-scroll overflow-x-hidden relative custom-scrollbar">
            <div class="w-[455px] flex flex-col gap-5 shrink-0">
                @foreach ($category->news->skip(1) as $article)
                <a href="{{ route('front.details', $article->slug) }}" class="card py-[2px]">
                    <div class="rounded-[20px] border border-[#EEF0F7] p-[14px] flex items-center gap-4 hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300">
                        <div class="w-[130px] h-[100px] flex shrink-0 rounded-[20px] overflow-hidden">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="object-cover w-full h-full" alt="{{ $article->name }}" />
                        </div>
                        <div class="flex flex-col gap-[6px]">
                            <h3 class="font-bold text-lg leading-[27px]">{{ $article->name }}</h3>
                            <p class="text-sm leading-[21px] text-[#A3A6AE]">{{ $article->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="sticky z-10 bottom-0 w-full h-[100px] bg-gradient-to-b from-[rgba(255,255,255,0.19)] to-[rgba(255,255,255,1)]"></div>
        </div>
        @endif
    </div>
</section>
@endforeach

@endsection
