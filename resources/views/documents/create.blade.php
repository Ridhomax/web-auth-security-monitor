
@extends('layouts.main')

@section('content')
<section class="container" style="padding: 40px 0 64px;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:28px;">
        <div>
            <h1 style="margin-bottom:8px;">Kelola Dokumen</h1>
            <p style="color:#666;">Kelola dokumen resmi untuk mahasiswa FIT.</p>
        </div>
        <a href="{{ route('admin.documents.create') }}"
           style="background:#287D35;color:white;padding:12px 18px;border-radius:8px;text-decoration:none;">
            + Tambah Dokumen
        </a>
    </div>

    @if (session('success'))
        <div style="padding:14px;background:#eaf7ec;color:#246b2d;border-radius:8px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="padding:14px;background:#fff0f0;color:#a11;border-radius:8px;margin-bottom:20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="text-align:left;background:#f5f7f5;">
                    <th style="padding:14px;">Judul</th>
                    <th style="padding:14px;">Kategori</th>
                    <th style="padding:14px;">Status</th>
                    <th style="padding:14px;">Unduhan</th>
                    <th style="padding:14px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:14px;">{{ $document->title }}</td>
                        <td style="padding:14px;">{{ $document->category }}</td>
                        <td style="padding:14px;">
                            {{ $document->is_active ? 'Aktif' : 'Nonaktif' }}
                        </td>
                        <td style="padding:14px;">{{ $document->download_count }}</td>
                        <td style="padding:14px;white-space:nowrap;">
                            <a href="{{ route('admin.documents.edit', $document) }}">Edit</a>
                            <form action="{{ route('admin.documents.destroy', $document) }}"
                                  method="POST" style="display:inline;margin-left:10px;"
                                  onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color:#b42318;border:0;background:none;cursor:pointer;">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:30px;text-align:center;color:#666;">
                            Belum ada dokumen. Klik Tambah Dokumen untuk mulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:24px;">{{ $documents->links() }}</div>
</section>
@endsection
