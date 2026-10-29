@extends('layouts.admin')

@section('title', 'Manajemen Berita | Admin FIT')

@section('content')

<section class="admin-page">

    {{-- HEADER --}}
    <div class="admin-page-header">
        <div>
            <h1>Manajemen Berita</h1>
            <p>Kelola informasi dan berita Kemahasiswaan FIT.</p>
        </div>

        <a href="{{ route('admin.news.create') }}"
           class="admin-btn admin-btn-primary">
            + Tambah Berita
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

    {{-- TABEL BERITA --}}
    <div class="admin-card">

        <div class="admin-table-wrapper">
            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal Publikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($news as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->title }}</strong>

                                <span class="admin-table-subtitle">
                                    {{ $item->slug }}
                                </span>
                            </td>

                            <td>
                                @if ($item->status === 'published')
                                    <span class="admin-badge admin-badge-success">
                                        Terbit
                                    </span>
                                @else
                                    <span class="admin-badge admin-badge-warning">
                                        Draft
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $item->published_at?->format('d M Y H:i') ?? 'Belum dijadwalkan' }}
                            </td>

                            <td>
                                <div class="admin-table-actions">

                                    <a href="{{ route('admin.news.edit', $item) }}"
                                       class="admin-action-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.news.destroy', $item) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus berita ini?')"
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
                            <td colspan="4" class="admin-table-empty">
                                Belum ada berita. Klik Tambah Berita untuk membuat berita pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="admin-pagination">
        {{ $news->links() }}
    </div>

</section>

@endsection