
@extends('layouts.main')

@section('title', 'Beranda | Kemahasiswaan FIT')

@section('content')

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-content">
            <span class="eyebrow">KEMAHASISWAAN FAKULTAS</span>

            <h1>Kembangkan Potensi, <span>Raih Prestasi.</span></h1>

            <p class="hero-description">
                Satu portal untuk informasi kegiatan, beasiswa, prestasi,
                organisasi, dan layanan kemahasiswaan.
            </p>

            <div class="hero-actions">
                <a href="#layanan" class="btn-primary">
                    Jelajahi Layanan <span aria-hidden="true">→</span>
                </a>

                <a href="#informasi" class="btn-secondary">
                    Lihat Informasi
                </a>
            </div>

            <div class="hero-note">
                <span class="hero-note-dot"></span>
                Satu langkah menuju potensi terbaikmu
            </div>
        </div>

        <div class="hero-image-wrap">
            <img
                src="{{ asset('images/Depan Pintu Masuk FIT.jpeg') }}"
                alt="Bagian depan gedung FIT"
                class="hero-image"
            >

            <div class="image-caption">
                <span class="caption-mark"></span>
                <div>
                    <strong>Fakultas Informatika dan Teknik</strong>
                    <p>Ruang untuk tumbuh dan berprestasi</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-section" id="layanan">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">AKSES CEPAT</span>
                <h2>Apa yang kamu butuhkan?</h2>
                <p>Temukan informasi dan layanan kemahasiswaan dalam satu tempat.</p>
            </div>
        </div>

        <div class="service-grid">
            <a href="#informasi" class="service-card">
                <span class="service-icon icon-activity">01</span>
                <h3>Kegiatan</h3>
                <p>Informasi seminar, acara, dan kegiatan mahasiswa.</p>
                <span class="card-arrow" aria-hidden="true">↗</span>
            </a>

            <a href="#informasi" class="service-card">
                <span class="service-icon icon-scholarship">02</span>
                <h3>Beasiswa</h3>
                <p>Temukan informasi beasiswa dan peluang pendanaan studi.</p>
                <span class="card-arrow" aria-hidden="true">↗</span>
            </a>

            <a href="#informasi" class="service-card">
                <span class="service-icon icon-achievement">03</span>
                <h3>Prestasi</h3>
                <p>Kenali pencapaian mahasiswa dan laporkan prestasimu.</p>
                <span class="card-arrow" aria-hidden="true">↗</span>
            </a>

            <a href="#organisasi" class="service-card">
                <span class="service-icon icon-organization">04</span>
                <h3>Organisasi</h3>
                <p>Kenali organisasi dan komunitas mahasiswa di fakultas.</p>
                <span class="card-arrow" aria-hidden="true">↗</span>
            </a>

            <a href="{{ route('login') }}" class="service-card">
                <span class="service-icon icon-proposal">05</span>
                <h3>Pengajuan Proposal</h3>
                <p>Akses awal untuk kebutuhan administrasi kegiatan.</p>
                <span class="card-arrow" aria-hidden="true">↗</span>
            </a>

            <a href="{{ route('login') }}" class="service-card">
                <span class="service-icon icon-room">06</span>
                <h3>Peminjaman Ruangan</h3>
                <p>Masuk untuk mengakses layanan pengajuan ruangan.</p>
                <span class="card-arrow" aria-hidden="true">↗</span>
            </a>
        </div>
    </div>
</section>

<section class="news-section" id="informasi">
    <div class="container">
        <div class="news-heading">
            <div>
                <span class="eyebrow">KABAR FAKULTAS</span>
                <h2>Informasi Terbaru</h2>
                <p>Ikuti kabar dan informasi seputar kemahasiswaan.</p>
            </div>
        </div>


        @if(isset($news) && $news->isNotEmpty())
            <div class="news-grid">
                @foreach($news as $item)
                    <article class="news-card">
                        @if($item->image_path)
                            <a href="{{ route('news.show', $item->slug) }}"
                            class="news-card-image-link">
                                <img
                                    src="{{ asset('storage/' . $item->image_path) }}"
                                    alt="{{ $item->title }}"
                                    class="news-card-image"
                                    loading="lazy"
                                >
                            </a>
                        @else
                            <div class="news-card-image-placeholder">
                                <span>Kemahasiswaan FIT</span>
                            </div>
                        @endif

                        <div class="news-card-content">
                            <span class="news-card-date">
                                {{ $item->published_at->translatedFormat('d F Y') }}
                            </span>

                            <h3>
                                <a href="{{ route('news.show', $item->slug) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>

                            <p>
                                {{ $item->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($item->content), 140) }}
                            </p>

                            <a href="{{ route('news.show', $item->slug) }}"
                            class="news-card-link">
                                Baca Selengkapnya <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="news-empty">
                <div class="news-empty-icon" aria-hidden="true">✳</div>
                <h3>Informasi menarik segera hadir</h3>
                <p>
                    Berita kegiatan, peluang beasiswa, dan informasi prestasi
                    akan ditampilkan di bagian ini.
                </p>
            </div>
        @endif

</section>

<section class="organization-section" id="organisasi">
    <div class="container organization-banner">
        <div>
            <span class="eyebrow">BERTUMBUH BERSAMA</span>
            <h2>Mulai perjalananmu di luar ruang kelas.</h2>
            <p>
                Kembangkan kemampuan, bangun koneksi, dan temukan kesempatan
                untuk berkontribusi melalui kegiatan kemahasiswaan.
            </p>
        </div>

        <a href="#layanan" class="btn-light">
            Jelajahi Layanan <span aria-hidden="true">→</span>
        </a>
    </div>
</section>

@endsection
