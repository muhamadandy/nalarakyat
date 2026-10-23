@extends('front.layouts.app')

@section('title', ($keyword !== '' ? 'Pencarian: ' . $keyword : 'Pencarian') . ' - Nalarakyat')

@section('content')

<section id="heading" class="max-w-[1130px] mx-auto flex items-center flex-col gap-[30px] mt-[70px]">
    <h1 class="text-4xl leading-[45px] font-bold text-center">
        Jelajahi Berita Terkini <br />
        yang Sedang Ramai
    </h1>
    <form action="{{ route('front.search') }}" method="GET">
        <label for="search-bar" class="flex transition-all duration-300 ring-1 ring-[#E8EBF4] focus-within:ring-2 focus-within:ring-[#FF6B18] rounded-[50px] group"
               style="width:500px;padding:12px 20px;gap:10px">
            <div class="flex shrink-0" style="width:20px;height:20px">
                <img src="{{ asset('front/assets/images/icons/search-normal.svg') }}" alt="cari" />
            </div>
            <input
                autocomplete="off"
                type="text"
                id="search-bar"
                name="keyword"
                value="{{ $keyword }}"
                placeholder="Cari berita terkini..."
                class="appearance-none font-semibold placeholder:font-normal placeholder:text-[#A3A6AE] outline-none focus:ring-0 w-full"
            />
        </label>
    </form>
</section>

<section id="search-result" class="max-w-[1130px] mx-auto flex items-start flex-col gap-[30px] mt-[70px]" style="margin-bottom:100px">
    <h2 class="text-[26px] leading-[39px] font-bold">
        @if ($keyword !== '')
            Hasil Pencarian: <span>{{ $keyword }}</span>
        @else
            Semua Berita
        @endif
        <span class="text-sm font-normal text-[#A3A6AE]" style="margin-left:8px">({{ $articles->total() }} berita)</span>
    </h2>

    @if ($articles->isNotEmpty())
        <div id="search-cards" class="grid grid-cols-3 gap-[30px]">
            @foreach ($articles as $article)
                @include('front.partials.article-card', ['article' => $article])
            @endforeach
        </div>

        <div style="width:100%">
            {{ $articles->links('front.partials.pagination') }}
        </div>
    @else
        <p class="text-[#A3A6AE]" style="padding:40px 0">
            Tidak ada berita yang cocok dengan "{{ $keyword }}". Coba kata kunci lain.
        </p>
    @endif
</section>

{{-- ===================== IKLAN LEBAR (posisi: Footer) ===================== --}}
@if (!empty($banners['footer']) && $banners['footer']->isNotEmpty())
@php($wide = $banners['footer']->random())
<section id="Advertisement" class="max-w-[1130px] mx-auto flex justify-center" style="margin-bottom:70px">
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

@endsection
