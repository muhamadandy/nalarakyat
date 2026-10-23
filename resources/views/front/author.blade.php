@extends('front.layouts.app')

@section('title', $author->name . ' - Nalarakyat')

@section('content')

<section id="author" class="max-w-[1130px] mx-auto flex items-center flex-col gap-[30px] mt-[70px]">
    <div id="title" class="flex items-center gap-[30px]">
        <h1 class="text-4xl leading-[45px] font-bold">Berita Penulis</h1>
        <h1 class="text-4xl leading-[45px] font-bold">/</h1>
        <div class="flex gap-3 items-center">
            <div class="flex shrink-0 rounded-full overflow-hidden" style="width:60px;height:60px">
                <img src="{{ asset('storage/' . $author->avatar) }}" alt="{{ $author->name }}" class="object-cover w-full h-full" />
            </div>
            <div class="flex flex-col">
                <p class="text-lg leading-[27px] font-semibold">{{ $author->name }}</p>
                <span class="text-[#A3A6AE]">{{ $author->occupation }}</span>
            </div>
        </div>
    </div>

    @if ($articles->isNotEmpty())
        <div id="content-cards" class="grid grid-cols-3 gap-[30px]">
            @foreach ($articles as $article)
                @include('front.partials.article-card', ['article' => $article])
            @endforeach
        </div>

        {{ $articles->links('front.partials.pagination') }}
    @else
        <p class="text-[#A3A6AE]" style="padding:60px 0">Penulis ini belum memiliki berita.</p>
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
