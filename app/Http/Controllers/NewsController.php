<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function show(string $slug): View
    {
        $news = News::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return view('news.show', compact('news'));
    }
}
