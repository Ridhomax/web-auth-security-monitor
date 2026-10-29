
@extends('layouts.main')

@section('title', $news->title . ' | Kemahasiswaan FIT')

@section('content')
<article class="container" style="max-width: 900px; padding-top: 40px; padding-bottom: 60px;">

    <a href="{{ url('/') }}" style="color: #287D35; text-decoration: none;">
        &larr; Kembali ke Beranda
    </a>

    <header style="margin-top: 24px; margin-bottom: 28px;">
        <p style="color: #287D35; font-weight: 600; margin-bottom: 10px;">
            Informasi Kemahasiswaan FIT
        </p>

        <h1 style="font-size: clamp(28px, 5vw, 42px); line-height: 1.2; margin-bottom: 16px;">
            {{ $news->title }}
        </h1>

        <p style="color: #66736A;">
            Dipublikasikan pada
            {{ $news->published_at->translatedFormat('d F Y, H:i') }}
        </p>
    </header>

    @if ($news->image_path)
        <img
            src="{{ asset('storage/' . $news->image_path) }}"
            alt="{{ $news->title }}"
            style="width: 100%; max-height: 480px; height: auto; object-fit: contain; border-radius: 14px; margin-bottom: 28px;">
    @endif

    @if ($news->excerpt)
        <p style="font-size: 19px; line-height: 1.8; font-weight: 500; margin-bottom: 24px;">
            {{ $news->excerpt }}
        </p>
    @endif

    <div style="font-size: 17px; line-height: 1.9; color: #26332B; overflow-wrap: anywhere;">
        {!! nl2br(e($news->content)) !!}
    </div>

    <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
        <a href="{{ url('/') }}" style="color: #287D35; text-decoration: none; font-weight: 600;">
            &larr; Kembali ke Beranda
        </a>
    </div>

</article>
@endsection
