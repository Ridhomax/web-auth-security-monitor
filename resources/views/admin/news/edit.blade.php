@extends('layouts.admin')

@section('title', 'Edit Berita | Admin FIT')

@section('content')

<section class="admin-page admin-form-page">

    {{-- HEADER --}}
    <div class="admin-page-header">
        <div>
            <a href="{{ route('admin.news.index') }}"
               class="admin-back-link">
                &larr; Kembali ke Manajemen Berita
            </a>

            <h1>Edit Berita</h1>

            <p>
                Perbarui informasi berita Kemahasiswaan FIT.
            </p>
        </div>
    </div>

    {{-- VALIDASI ERROR --}}
    @if ($errors->any())
        <div class="admin-alert admin-alert-error" role="alert">
            <strong>Periksa kembali data berikut:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM EDIT BERITA --}}
    <form
        action="{{ route('admin.news.update', $news) }}"
        method="POST"
        enctype="multipart/form-data"
        class="admin-card admin-form"
    >
        @csrf
        @method('PUT')

        {{-- JUDUL BERITA --}}
        <div class="admin-form-group">
            <label for="title">
                Judul Berita <span class="required">*</span>
            </label>

            <input
                id="title"
                name="title"
                type="text"
                value="{{ old('title', $news->title) }}"
                maxlength="255"
                required
            >
        </div>

        {{-- RINGKASAN BERITA --}}
        <div class="admin-form-group">
            <label for="excerpt">
                Ringkasan Berita
            </label>

            <textarea
                id="excerpt"
                name="excerpt"
                rows="3"
                maxlength="1000"
            >{{ old('excerpt', $news->excerpt) }}</textarea>

            <small>
                Ringkasan singkat yang ditampilkan pada kartu berita.
            </small>
        </div>

        {{-- ISI BERITA --}}
        <div class="admin-form-group">
            <label for="content">
                Isi Berita <span class="required">*</span>
            </label>

            <textarea
                id="content"
                name="content"
                rows="10"
                required
            >{{ old('content', $news->content) }}</textarea>
        </div>

        {{-- GAMBAR SAAT INI --}}
        @if ($news->image_path)
            <div class="admin-form-group">
                <label>Gambar Saat Ini</label>

                <div class="admin-image-preview">
                    <img
                        src="{{ asset('storage/' . $news->image_path) }}"
                        alt="Gambar berita: {{ $news->title }}"
                    >
                </div>

                <small>
                    Gambar lama tetap digunakan jika tidak ada
                    gambar baru yang diunggah.
                </small>
            </div>
        @endif

        {{-- GANTI GAMBAR --}}
        <div class="admin-form-group">
            <label for="image">
                Ganti Gambar (Opsional)
            </label>

            <input
                id="image"
                name="image"
                type="file"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >

            <small>
                Format JPG, PNG, atau WebP. Maksimal 2 MB.
            </small>
        </div>

        {{-- STATUS BERITA --}}
        <div class="admin-form-group">
            <label for="status">
                Status Berita <span class="required">*</span>
            </label>

            <select
                id="status"
                name="status"
                required
            >
                <option
                    value="draft"
                    @selected(old('status', $news->status) === 'draft')
                >
                    Simpan sebagai Draft
                </option>

                <option
                    value="published"
                    @selected(old('status', $news->status) === 'published')
                >
                    Terbitkan
                </option>
            </select>
        </div>

        {{-- TANGGAL PUBLIKASI --}}
        <div
            class="admin-form-group"
            id="published-at-field"
        >
            <label for="published_at">
                Tanggal dan Waktu Publikasi
                <span class="required">*</span>
            </label>

            <input
                id="published_at"
                name="published_at"
                type="datetime-local"
                value="{{ old('published_at', $news->published_at?->format('Y-m-d\TH:i')) }}"
            >

            <small>
                Berita akan terlihat oleh publik setelah
                waktu publikasinya tercapai.
            </small>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-btn admin-btn-primary"
            >
                Simpan Perubahan
            </button>

            <a
                href="{{ route('admin.news.index') }}"
                class="admin-btn admin-btn-secondary"
            >
                Batal
            </a>

        </div>

    </form>

</section>

<script>
    const statusField = document.getElementById('status');
    const publishedAtField = document.getElementById('published-at-field');
    const publishedAtInput = document.getElementById('published_at');

    function togglePublishedAt() {
        const isPublished = statusField.value === 'published';

        publishedAtField.hidden = !isPublished;
        publishedAtInput.required = isPublished;
    }

    statusField.addEventListener('change', togglePublishedAt);

    togglePublishedAt();
</script>

@endsection