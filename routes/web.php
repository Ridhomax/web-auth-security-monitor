
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SecurityDashboardController;
use App\Http\Controllers\Admin\SecurityEventController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\NewsController;
use App\Models\News;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', function () {
    $news = News::query()
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->orderByDesc('published_at')
        ->take(3)
        ->get();

    return view('welcome', compact('news'));
});

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/admin/security-dashboard', [
    SecurityDashboardController::class,
    'index'
])
    ->middleware(['auth', 'admin'])
    ->name('admin.security-dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get(
        '/admin/security-events/{securityEvent}',
        [SecurityEventController::class, 'show']
    )->name('admin.security-events.show');

    Route::patch(
        '/admin/security-events/{securityEvent}/resolve',
        [SecurityEventController::class, 'resolve']
    )->name('admin.security-events.resolve');
});


Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin Utama
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Manajemen Berita
        Route::resource('news', AdminNewsController::class)
            ->except(['show']);

        // Manajemen Dokumen
        Route::resource('documents', AdminDocumentController::class)
            ->except(['show']);

    });

Route::get('/berita/{slug}', [NewsController::class, 'show'])
    ->name('news.show');

    // Pusat Unduhan untuk mahasiswa
Route::get('/dokumen', [DocumentController::class, 'index'])
    ->name('documents.index');

Route::get('/dokumen/{document}/unduh', [DocumentController::class, 'download'])
    ->name('documents.download');
