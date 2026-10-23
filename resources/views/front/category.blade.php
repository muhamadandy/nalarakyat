@extends('front.layouts.app')

@section('title', $category->name . ' - Nalarakyat')

@section('content')

<section id="Category-result" class="max-w-[1130px] mx-auto flex items-center flex-col gap-[30px] mt-[70px]">
    <h1 class="text-4xl leading-[45px] font-bold text-center">
        Jelajahi Berita <br />
        {{ $category->name }}
    </h1>

    @if ($articles->isNotEmpty())
        <div id="search-cards" class="grid grid-cols-3 gap-[30px]">
            @foreach ($articles as $article)
                @include('front.partials.article-card', ['article' => $article])
            @endforeach
        </div>

        {{ $articles->links('front.partials.pagination') }}
    @else
        <p class="text-[#A3A6AE]" style="padding:60px 0">Belum ada berita di kategori ini.</p>
    @endif
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

@endsection
