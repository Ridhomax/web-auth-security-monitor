<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Document;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Statistik berita
        $totalNews = News::count();

        $publishedNews = News::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        // Statistik dokumen
        $totalDocuments = Document::count();

        $totalDownloads = Document::sum('download_count');

        // Lima berita terbaru
        $latestNews = News::query()
            ->latest()
            ->take(5)
            ->get();

        // Lima dokumen terbaru
        $latestDocuments = Document::query()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalNews',
            'publishedNews',
            'totalDocuments',
            'totalDownloads',
            'latestNews',
            'latestDocuments'
        ));
    }
}