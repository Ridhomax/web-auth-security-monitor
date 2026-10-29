<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query()
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $documents = $query
            ->orderBy('category')
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        $categories = Document::query()
            ->where('is_active', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('documents.index', compact(
            'documents',
            'categories'
        ));
    }

    public function download(Document $document): StreamedResponse
    {
        abort_unless($document->is_active, 404);

        abort_unless(
            Storage::disk('local')->exists($document->file_path),
            404,
            'File dokumen tidak ditemukan.'
        );

        $document->increment('download_count');

        return Storage::disk('local')->download(
            $document->file_path,
            $document->original_filename
        );
    }
}
