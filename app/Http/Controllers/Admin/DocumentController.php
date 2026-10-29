<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::query()
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
                'max:10240',
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'local');

        $base = Str::slug($validated['title']) ?: 'dokumen';
        $slug = $base;
        $number = 2;

        while (Document::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $number++;
        }

        try {
            Document::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'category' => $validated['category'],
                'description' => $validated['description'] ?? null,
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'is_active' => $request->boolean('is_active'),
            ]);
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($path);
            throw $e;
        }

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(Document $document)
    {
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
                'max:10240',
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $newPath = null;
        $oldPath = $document->file_path;
        $file = $request->file('file');

        if ($file) {
            $newPath = $file->store('documents', 'local');
        }

        $base = Str::slug($validated['title']) ?: 'dokumen';
        $slug = $base;
        $number = 2;

        while (
            Document::where('slug', $slug)
                ->where('id', '!=', $document->id)
                ->exists()
        ) {
            $slug = $base . '-' . $number++;
        }

        try {
            $document->update([
                'title' => $validated['title'],
                'slug' => $slug,
                'category' => $validated['category'],
                'description' => $validated['description'] ?? null,
                'is_active' => $request->boolean('is_active'),
                ...($newPath ? [
                    'file_path' => $newPath,
                    'original_filename' => $file->getClientOriginalName(),
                ] : []),
            ]);
        } catch (\Throwable $e) {
            if ($newPath) {
                Storage::disk('local')->delete($newPath);
            }
            throw $e;
        }

        if ($newPath) {
            Storage::disk('local')->delete($oldPath);
        }

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        $path = $document->file_path;

        $document->delete();
        Storage::disk('local')->delete($path);

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }
}
