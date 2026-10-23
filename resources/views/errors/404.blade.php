@extends('front.layouts.app')

@section('title', 'Halaman Tidak Ditemukan - Nalarakyat')

@section('content')
<section class="max-w-[1130px] mx-auto flex items-center flex-col" style="margin-top:100px;gap:16px;text-align:center">
    <p style="font-size:96px;line-height:1;font-weight:800;color:#b91c1c">404</p>
    <h1 class="text-4xl leading-[45px] font-bold">Halaman Tidak Ditemukan</h1>
    <p class="text-[#A3A6AE]" style="max-width:480px">
        Halaman yang kamu cari tidak ada, sudah dipindahkan, atau beritanya sudah dihapus.
    </p>
    <a href="{{ route('front.index') }}" class="rounded-full font-bold text-white"
       style="background:#b91c1c;padding:12px 22px;margin-top:12px">
        Kembali ke Beranda
    </a>
</section>
@endsection
