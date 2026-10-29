@extends('layouts.admin')

@section('title', 'Edit Dokumen | Admin FIT')

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

            <h1>Edit Dokumen</h1>

            <p>
                Perbarui informasi atau ganti file dokumen.
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

    {{-- FORM EDIT DOKUMEN --}}
    <form
        action="{{ route('admin.documents.update', $document) }}"
        method="POST"
        enctype="multipart/form-data"
        class="admin-card admin-form"
    >
        @csrf
        @method('PUT')

        {{-- JUDUL DOKUMEN --}}
        <div class="admin-form-group">
            <label for="title">
                Judul Dokumen <span class="required">*</span>
            </label>

            <input
                id="title"
                name="title"
                type="text"
                value="{{ old('title', $document->title) }}"
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
                value="{{ old('category', $document->category) }}"
                required
            >

            <small>
                Kategori digunakan untuk mengelompokkan dokumen.
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
            >{{ old('description', $document->description) }}</textarea>

            <small>
                Jelaskan secara singkat isi atau kegunaan dokumen.
            </small>
        </div>

        {{-- FILE SAAT INI --}}
        <div class="admin-form-group">
            <label>File Saat Ini</label>

            <div class="admin-current-file">
                <span class="admin-file-icon" aria-hidden="true">
                    📄
                </span>

                <div class="admin-file-info">
                    <strong>
                        {{ $document->original_filename }}
                    </strong>

                    <small>
                        File yang saat ini tersimpan.
                    </small>
                </div>
            </div>
        </div>

        {{-- GANTI FILE --}}
        <div class="admin-form-group">
            <label for="file">
                Ganti File (Opsional)
            </label>

            <input
                id="file"
                name="file"
                type="file"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
            >

            <small>
                Format PDF, DOC, DOCX, XLS, XLSX, PPT, atau PPTX.
                Maksimal 10 MB.
            </small>

            <small>
                Kosongkan jika tidak ingin mengganti file sebelumnya.
            </small>
        </div>

        {{-- STATUS DOKUMEN --}}
        <div class="admin-form-group">

            <label for="is_active" class="admin-checkbox-label">

                <input
                    id="is_active"
                    name="is_active"
                    type="checkbox"
                    value="1"
                    @checked(old('is_active', $document->is_active ? '1' : '0') == '1')
                >

                <span>
                    Tampilkan kepada mahasiswa
                </span>

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
                Simpan Perubahan
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