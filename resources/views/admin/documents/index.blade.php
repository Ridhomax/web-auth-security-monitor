@extends('layouts.admin')

@section('title', 'Manajemen Dokumen | Admin FIT')

@section('content')

<section class="admin-page">

    {{-- HEADER --}}
    <div class="admin-page-header">
        <div>
            <h1>Manajemen Dokumen</h1>
            <p>
                Kelola dokumen resmi Kemahasiswaan FIT.
            </p>
        </div>

        <a
            href="{{ route('admin.documents.create') }}"
            class="admin-btn admin-btn-primary"
        >
            + Tambah Dokumen
        </a>
    </div>

    {{-- NOTIFIKASI BERHASIL --}}
    @if (session('success'))
        <div class="admin-alert admin-alert-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    {{-- NOTIFIKASI ERROR --}}
    @if ($errors->any())
        <div class="admin-alert admin-alert-error" role="alert">
            <strong>Terjadi kesalahan:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TABEL DOKUMEN --}}
    <div class="admin-card">

        <div class="admin-table-wrapper">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Judul Dokumen</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Unduhan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($documents as $document)

                        <tr>
                            {{-- JUDUL --}}
                            <td>
                                <strong>
                                    {{ $document->title }}
                                </strong>
                            </td>

                            {{-- KATEGORI --}}
                            <td>
                                {{ $document->category }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if ($document->is_active)
                                    <span class="admin-badge admin-badge-success">
                                        Aktif
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge-warning">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- JUMLAH UNDUHAN --}}
                            <td>
                                {{ number_format($document->download_count, 0, ',', '.') }}
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div class="admin-table-actions">

                                    <a
                                        href="{{ route('admin.documents.edit', $document) }}"
                                        class="admin-action-edit"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.documents.destroy', $document) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="admin-action-delete"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="admin-table-empty">
                                Belum ada dokumen.
                                Klik Tambah Dokumen untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="admin-pagination">
        {{ $documents->links() }}
    </div>

</section>

@endsection