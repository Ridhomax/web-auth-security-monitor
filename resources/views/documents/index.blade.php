@extends('layouts.main')

@section('content')

<style>
    /* ===== HALAMAN DOKUMEN ===== */

    .documents-page {
        padding-top: 40px;
        padding-bottom: 64px;
    }

    .documents-heading {
        margin-bottom: 32px;
    }

    .documents-heading h1 {
        margin-bottom: 10px;
        font-size: 32px;
        line-height: 1.3;
        color: #1f2937;
    }

    .documents-heading p {
        color: #666;
        line-height: 1.7;
    }

    /* ===== PENCARIAN DAN FILTER ===== */

    .documents-search {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 180px auto auto;
        gap: 12px;
        align-items: stretch;
        margin-bottom: 32px;
    }

    .documents-search input,
    .documents-search select {
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
        font-size: 14px;
    }

    .documents-search button {
        padding: 12px 20px;
        background: #287D35;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
    }

    .documents-search button:hover {
        background: #20652b;
    }

    .documents-reset {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 12px 18px;
        border: 1px solid #ddd;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
        background: #fff;
    }

    /* ===== GRID DOKUMEN ===== */

    .documents-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        width: 100%;
    }

    /* ===== KARTU DOKUMEN ===== */

    .document-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        box-sizing: border-box;
        padding: 24px;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        background: #fff;
        transition: box-shadow 0.2s ease;
    }

    .document-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }

    .document-category {
        display: inline-block;
        max-width: 100%;
        box-sizing: border-box;
        padding: 6px 12px;
        background: #eaf7ec;
        color: #287D35;
        border-radius: 8px;
        font-size: 12px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .document-title {
        margin-top: 16px;
        margin-bottom: 12px;
        font-size: 19px;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .document-description {
        color: #666;
        font-size: 14px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .document-footer {
        margin-top: auto;
        padding-top: 24px;
    }

    .document-download-count {
        color: #888;
        font-size: 13px;
        margin-bottom: 18px;
    }

    .document-download {
        display: block;
        width: 100%;
        box-sizing: border-box;
        text-align: center;
        padding: 13px 16px;
        background: #287D35;
        color: #fff;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .document-download:hover {
        background: #20652b;
    }

    /* ===== EMPTY STATE ===== */

    .documents-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        background: #f8faf8;
        border-radius: 12px;
    }

    .documents-empty h3 {
        margin-bottom: 12px;
    }

    .documents-empty p {
        color: #666;
    }

    /* ===== TABLET ===== */

    @media (max-width: 900px) {
        .documents-search {
            grid-template-columns: minmax(0, 1fr) 180px;
        }

        .documents-search button,
        .documents-reset {
            width: 100%;
            box-sizing: border-box;
        }
    }

    /* ===== SMARTPHONE ===== */

    @media (max-width: 768px) {

        .documents-page {
            padding-top: 28px;
            padding-bottom: 48px;
        }

        .documents-heading {
            margin-bottom: 24px;
        }

        .documents-heading h1 {
            font-size: 26px;
            line-height: 1.3;
        }

        .documents-heading p {
            font-size: 14px;
            line-height: 1.7;
        }

        .documents-search {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }

        .documents-search input,
        .documents-search select {
            grid-column: 1 / -1;
            width: 100%;
            min-width: 0;
            font-size: 14px;
        }

        .documents-search button,
        .documents-reset {
            width: 100%;
            min-width: 0;
            padding: 12px 8px;
            font-size: 13px;
        }

        .documents-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 16px;
        }

        .document-card {
            width: 100%;
            min-width: 0;
            padding: 20px;
        }

        .document-title {
            font-size: 18px;
        }

        .document-description {
            font-size: 14px;
        }

        .document-footer {
            padding-top: 20px;
        }
    }
</style>

<section class="container documents-page">

    {{-- JUDUL HALAMAN --}}
    <div class="documents-heading">
        <h1>Pusat Unduhan Dokumen</h1>

        <p>
            Unduh formulir, template, dan dokumen resmi
            Kemahasiswaan Fakultas Ilmu Terapan.
        </p>
    </div>

    {{-- PENCARIAN DAN FILTER --}}
    <form
        method="GET"
        action="{{ route('documents.index') }}"
        class="documents-search"
    >

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari dokumen..."
            aria-label="Cari dokumen"
        >

        <select
            name="category"
            aria-label="Filter kategori dokumen"
        >
            <option value="">Semua Kategori</option>

            @foreach ($categories as $category)
                <option
                    value="{{ $category }}"
                    @selected(request('category') == $category)
                >
                    {{ $category }}
                </option>
            @endforeach
        </select>

        <button type="submit">
            Cari Dokumen
        </button>

        <a
            href="{{ route('documents.index') }}"
            class="documents-reset"
        >
            Reset
        </a>

    </form>

    {{-- DAFTAR DOKUMEN --}}
    <div class="documents-grid">

        @forelse ($documents as $document)

            <article class="document-card">

                <div>
                    <span class="document-category">
                        {{ $document->category }}
                    </span>

                    <h3 class="document-title">
                        {{ $document->title }}
                    </h3>

                    <p class="document-description">
                        {{ $document->description ?: 'Dokumen resmi Kemahasiswaan FIT.' }}
                    </p>
                </div>

                <div class="document-footer">

                    <p class="document-download-count">
                        Diunduh {{ $document->download_count }} kali
                    </p>

                    <a
                        href="{{ route('documents.download', $document) }}"
                        class="document-download"
                    >
                        ↓ Unduh Dokumen
                    </a>

                </div>

            </article>

        @empty

            <div class="documents-empty">

                <h3>Dokumen Tidak Ditemukan</h3>

                <p>
                    Belum ada dokumen yang tersedia
                    atau sesuai dengan pencarian.
                </p>

            </div>

        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div style="margin-top:32px;">
        {{ $documents->links() }}
    </div>

</section>

@endsection