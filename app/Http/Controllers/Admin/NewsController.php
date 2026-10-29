<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::query()
            ->latest()
            ->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:12288',
            ],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => [
                'nullable',
                'date',
                'required_if:status,published',
            ],
        ]);

        $imagePath = null;

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')
                    ->store('news', 'public');

                if ($imagePath === false) {
                    throw new \RuntimeException(
                        'Gagal menyimpan gambar berita.'
                    );
                }
            }

            $slug = $this->generateUniqueSlug($validated['title']);

            News::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'excerpt' => $validated['excerpt'] ?? null,
                'content' => $validated['content'],
                'image_path' => $imagePath,
                'status' => $validated['status'],
                'published_at' => $validated['status'] === 'published'
                    ? $validated['published_at']
                    : null,
            ]);

        } catch (\Throwable $e) {

            // Hapus gambar yang terlanjur diunggah jika penyimpanan gagal.
            if ($imagePath !== null && $imagePath !== false) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(News $news): View
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:12288',
            ],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => [
                'nullable',
                'date',
                'required_if:status,published',
            ],
        ]);

        $oldImagePath = $news->image_path;
        $newImagePath = null;

        try {
            if ($request->hasFile('image')) {
                $newImagePath = $request->file('image')
                    ->store('news', 'public');

                if ($newImagePath === false) {
                    throw new \RuntimeException(
                        'Gagal menyimpan gambar berita.'
                    );
                }
            }

            $slug = $news->slug;

            if ($validated['title'] !== $news->title) {
                $slug = $this->generateUniqueSlug(
                    $validated['title'],
                    $news->id
                );
            }

            $news->update([
                'title' => $validated['title'],
                'slug' => $slug,
                'excerpt' => $validated['excerpt'] ?? null,
                'content' => $validated['content'],
                'image_path' => $newImagePath ?? $oldImagePath,
                'status' => $validated['status'],
                'published_at' => $validated['status'] === 'published'
                    ? $validated['published_at']
                    : null,
            ]);

        } catch (\Throwable $e) {

            if ($newImagePath !== null && $newImagePath !== false) {
                try {
                    $deleted = Storage::disk('public')->delete($newImagePath);

                    if (!$deleted) {
                        Log::warning(
                            'Gagal membersihkan gambar baru setelah pembaruan berita gagal.',
                            [
                                'news_id' => $news->id,
                                'image_path' => $newImagePath,
                            ]
                        );
                    }
                } catch (\Throwable $cleanupException) {
                    Log::error(
                        'Terjadi kesalahan saat membersihkan gambar baru.',
                        [
                            'news_id' => $news->id,
                            'image_path' => $newImagePath,
                            'error' => $cleanupException->getMessage(),
                        ]
                    );
                }
            }

            throw $e;
        }

        if (
            $newImagePath !== null &&
            $oldImagePath !== null &&
            $newImagePath !== $oldImagePath
        ) {
            try {
                $deleted = Storage::disk('public')->delete($oldImagePath);

                if (!$deleted) {
                    Log::warning(
                        'Gagal menghapus gambar lama setelah berita diperbarui.',
                        [
                            'news_id' => $news->id,
                            'image_path' => $oldImagePath,
                        ]
                    );
                }
            } catch (\Throwable $cleanupException) {
                Log::error(
                    'Terjadi kesalahan saat menghapus gambar lama berita.',
                    [
                        'news_id' => $news->id,
                        'image_path' => $oldImagePath,
                        'error' => $cleanupException->getMessage(),
                    ]
                );
            }
        }

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $newsId = $news->id;
        $imagePath = $news->image_path;

        $news->delete();

        if ($imagePath !== null) {
            try {
                $deleted = Storage::disk('public')->delete($imagePath);

                if (!$deleted) {
                    Log::warning('Gagal menghapus gambar berita setelah berita dihapus.', [
                        'news_id' => $newsId,
                        'image_path' => $imagePath,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('Terjadi kesalahan saat menghapus gambar berita.', [
                    'news_id' => $newsId,
                    'image_path' => $imagePath,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    private function generateUniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($title);

        if ($baseSlug === '') {
            $baseSlug = 'berita';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (
            News::query()
                ->where('slug', $slug)
                ->when(
                    $ignoreId !== null,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
