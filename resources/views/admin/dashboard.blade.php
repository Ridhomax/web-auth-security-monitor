@extends('layouts.admin')

@section('title', 'Dashboard Admin - Kemahasiswaan FIT')

@section('content')

<style>
    .admin-dashboard .container {
        width: 100%;
        max-width: 1280px;
        margin-inline: auto;
    }

    .admin-dashboard {
        padding: 50px 0 80px;
        background: #f8faf8;
        min-height: 80vh;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 32px;
    }

    .dashboard-header h1 {
        font-size: 30px;
        color: #26332b;
    }

    .dashboard-header p {
        margin-top: 6px;
        color: #66736a;
    }

    .dashboard-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .dashboard-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 18px;
        border-radius: 8px;
        background: #287d35;
        color: white;
        font-size: 13px;
        font-weight: 600;
        transition: background 0.2s ease;
    }

    .dashboard-btn:hover {
        background: #20652b;
    }

    .dashboard-btn-secondary {
        background: white;
        color: #287d35;
        border: 1px solid #dce9dc;
    }

    .dashboard-btn-secondary:hover {
        background: #edf7ed;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 35px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border: 1px solid #e3eee3;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }

    .stat-card p {
        color: #66736a;
        font-size: 13px;
    }

    .stat-card h2 {
        margin-top: 12px;
        color: #287d35;
        font-size: 34px;
        line-height: 1.2;
    }

    .dashboard-section {
        margin-top: 30px;
        padding: 25px;
        background: white;
        border: 1px solid #e3eee3;
        border-radius: 14px;
    }

    .dashboard-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .dashboard-section-header h2 {
        font-size: 20px;
        color: #26332b;
    }

    .dashboard-section-header a {
        color: #287d35;
        font-size: 13px;
        font-weight: 600;
    }

    .dashboard-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .dashboard-table th,
    .dashboard-table td {
        padding: 13px 15px;
        border-bottom: 1px solid #edf1ed;
        font-size: 13px;
    }

    .dashboard-table th {
        background: #f5faf5;
        color: #26332b;
        font-weight: 700;
    }

    .dashboard-table td {
        color: #66736a;
    }

    .dashboard-empty {
        padding: 20px;
        color: #66736a;
        text-align: center;
    }

    @media (max-width: 1000px) {
        .dashboard-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .admin-dashboard {
            padding: 30px 0 50px;
        }

        .dashboard-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-header h1 {
            font-size: 25px;
        }

        .dashboard-stats {
            gap: 12px;
        }

        .stat-card {
            padding: 18px;
        }

        .stat-card h2 {
            font-size: 27px;
        }

        .dashboard-section {
            padding: 18px;
        }

        .dashboard-table {
            min-width: 550px;
        }
    }
</style>

            <div class="admin-dashboard">
    <div class="container">

        <div class="dashboard-header">
            <div>
                <h1>Dashboard Admin</h1>
                <p>
                    Ringkasan aktivitas dan pengelolaan
                    website Kemahasiswaan FIT.
                </p>
            </div>

            <div class="dashboard-actions">
                <a href="{{ route('admin.news.index') }}"
                   class="dashboard-btn">
                    Manajemen Berita
                </a>

                <a href="{{ route('admin.documents.index') }}"
                   class="dashboard-btn dashboard-btn-secondary">
                    Manajemen Dokumen
                </a>
            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="dashboard-stats">

            <div class="stat-card">
                <p>Total Berita</p>
                <h2>{{ number_format($totalNews, 0, ',', '.') }}</h2>
            </div>

            <div class="stat-card">
                <p>Berita Dipublikasikan</p>
                <h2>{{ number_format($publishedNews, 0, ',', '.') }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Dokumen</p>
                <h2>{{ number_format($totalDocuments, 0, ',', '.') }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Unduhan</p>
                <h2>{{ number_format($totalDownloads, 0, ',', '.') }}</h2>
            </div>

        </div>

        {{-- BERITA TERBARU --}}
        <section class="dashboard-section">

            <div class="dashboard-section-header">
                <h2>Berita Terbaru</h2>

                <a href="{{ route('admin.news.index') }}">
                    Lihat Semua →
                </a>
            </div>

            <div class="dashboard-table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Judul Berita</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($latestNews as $news)
                            <tr>
                                <td>{{ $news->title }}</td>
                                <td>{{ ucfirst($news->status) }}</td>
                                <td>
                                    {{ $news->created_at?->format('d M Y') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="dashboard-empty">
                                    Belum ada berita.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>

        {{-- DOKUMEN TERBARU --}}
        <section class="dashboard-section">

            <div class="dashboard-section-header">
                <h2>Dokumen Terbaru</h2>

                <a href="{{ route('admin.documents.index') }}">
                    Lihat Semua →
                </a>
            </div>

            <div class="dashboard-table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Judul Dokumen</th>
                            <th>Kategori</th>
                            <th>Total Unduhan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($latestDocuments as $document)
                            <tr>
                                <td>{{ $document->title }}</td>
                                <td>{{ $document->category }}</td>
                                <td>
                                    {{ number_format($document->download_count, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="dashboard-empty">
                                    Belum ada dokumen.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>

    </div>
</div>

@endsection