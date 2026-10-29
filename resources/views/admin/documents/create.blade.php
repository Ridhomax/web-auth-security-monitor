@extends('layouts.admin')

@section('title', 'Tambah Dokumen | Admin FIT')

@section('content')

<section class="admin-page admin-form-page">

    {{-- HEADER --}}
    <div class="admin-page-header">
        <div>
            <a
                href="{{ route('admin.documents.index') }}"
                class="admin-back-link"
            >
                &larr; Kembali ke Manajemen Dokumen
            </a>

            <h1>Tambah Dokumen</h1>

            <p>
                Unggah dokumen resmi Kemahasiswaan FIT.
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

    {{-- FORM TAMBAH DOKUMEN --}}
    <form
        action="{{ route('admin.documents.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="admin-card admin-form"
    >
        @csrf

        {{-- JUDUL DOKUMEN --}}
        <div class="admin-form-group">
            <label for="title">
                Judul Dokumen <span class="required">*</span>
            </label>

            <input
                id="title"
                name="title"
                type="text"
                value="{{ old('title') }}"
                required
            >
        </div>

        {{-- KATEGORI --}}
        <div class="admin-form-group">
            <label for="category">
                Kategori <span class="required">*</span>
            </label>

            <input
                id="category"
                name="category"
                type="text"
                value="{{ old('category') }}"
                placeholder="Contoh: Proposal Kegiatan"
                required
            >

            <small>
                Masukkan kategori sesuai jenis dokumen.
            </small>
        </div>

        {{-- DESKRIPSI --}}
        <div class="admin-form-group">
            <label for="description">
                Deskripsi Dokumen
            </label>

            <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Tuliskan deskripsi singkat dokumen..."
            >{{ old('description') }}</textarea>

            <small>
                Jelaskan secara singkat isi atau kegunaan dokumen.
            </small>
        </div>

        {{-- UPLOAD FILE --}}
        <div class="admin-form-group">
            <label for="file">
                File Dokumen <span class="required">*</span>
            </label>

            <input
                id="file"
                name="file"
                type="file"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                required
            >

            <small>
                Format PDF, DOC, DOCX, XLS, XLSX, PPT, atau PPTX.
                Maksimal 10 MB.
            </small>
        </div>

        {{-- STATUS DOKUMEN --}}
        <div class="admin-form-group">

            <label for="is_active" class="admin-checkbox-label">

                <input
                    type="checkbox"
                    id="is_active"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', '1') == '1')
                >

                <span>Tampilkan kepada mahasiswa</span>

            </label>

            <small>
                Jika diaktifkan, dokumen dapat dilihat
                dan diunduh melalui halaman publik.
            </small>

        </div>

        {{-- TOMBOL AKSI --}}
        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-btn admin-btn-primary"
            >
                Simpan Dokumen
            </button>

            <a
                href="{{ route('admin.documents.index') }}"
                class="admin-btn admin-btn-secondary"
            >
                Batal
            </a>

        </div>

    </form>

</section>

@endsection