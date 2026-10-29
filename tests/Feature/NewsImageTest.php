<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class NewsImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_failed_database_update_preserves_old_image_and_removes_new_image(): void
    {
        $admin = $this->createAdmin();

        // Siapkan gambar lama di penyimpanan pengujian.
        Storage::disk('public')->put(
            'news/old-image.jpg',
            'old image content'
        );

        $news = $this->createNews('news/old-image.jpg');

        // Simulasikan kegagalan database setiap kali berita diperbarui.
        DB::unprepared("
            CREATE TRIGGER fail_news_update
            BEFORE UPDATE ON news
            BEGIN
                SELECT RAISE(ABORT, 'simulated database failure');
            END;
        ");

        try {
            $this->withoutExceptionHandling()
                ->actingAs($admin)
                ->put(
                    route('admin.news.update', $news),
                    [
                        'title' => 'Berita Diperbarui',
                        'content' => 'Konten berita diperbarui.',
                        'status' => 'draft',
                        'image' => UploadedFile::fake()->image('new-image.jpg'),
                    ]
                );

            $this->fail('Pembaruan database seharusnya gagal.');
        } catch (QueryException $e) {
            $this->assertStringContainsString(
                'simulated database failure',
                $e->getMessage()
            );
        }

        // Pastikan data berita tidak berubah.
        $news->refresh();

        $this->assertSame('Berita Asli', $news->title);
        $this->assertSame('news/old-image.jpg', $news->image_path);

        // Pastikan gambar lama tetap tersedia.
        Storage::disk('public')->assertExists('news/old-image.jpg');

        // Pastikan tidak ada gambar baru yang tertinggal.
        $this->assertCount(
            1,
            Storage::disk('public')->files('news')
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Test Admin',
            'email' => 'admin-news-image@example.com',
            'password' => bcrypt('PasswordTest123!'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function createNews(string $imagePath): News
    {
        return News::create([
            'title' => 'Berita Asli',
            'slug' => 'berita-asli',
            'excerpt' => 'Ringkasan berita.',
            'content' => 'Konten berita asli.',
            'image_path' => $imagePath,
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_replace_news_image(): void
    {
        $admin = $this->createAdmin();

        Storage::disk('public')->put(
            'news/old-image.jpg',
            'old image content'
        );

        $news = $this->createNews('news/old-image.jpg');

        $response = $this->actingAs($admin)->put(
            route('admin.news.update', $news),
            [
                'title' => 'Berita Diperbarui',
                'content' => 'Konten berita diperbarui.',
                'status' => 'draft',
                'image' => UploadedFile::fake()->image('new-image.jpg'),
            ]
        );

        $response->assertRedirect(route('admin.news.index'));

        $news->refresh();

        $this->assertNotEquals(
            'news/old-image.jpg',
            $news->image_path
        );

        Storage::disk('public')->assertExists($news->image_path);
        Storage::disk('public')->assertMissing('news/old-image.jpg');
    }

    public function test_updating_news_without_new_image_preserves_old_image(): void
    {
        $admin = $this->createAdmin();

        Storage::disk('public')->put(
            'news/old-image.jpg',
            'old image content'
        );

        $news = $this->createNews('news/old-image.jpg');

        $response = $this->actingAs($admin)->put(
            route('admin.news.update', $news),
            [
                'title' => 'Judul Baru',
                'content' => 'Konten baru.',
                'status' => 'draft',
            ]
        );

        $response->assertRedirect(route('admin.news.index'));

        $news->refresh();

        $this->assertSame(
            'news/old-image.jpg',
            $news->image_path
        );

        Storage::disk('public')->assertExists('news/old-image.jpg');
    }
    public function test_admin_can_create_news_with_image(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(
            route('admin.news.store'),
            [
                'title' => 'Berita Baru',
                'content' => 'Konten berita baru.',
                'status' => 'draft',
                'image' => UploadedFile::fake()->image('news-image.jpg'),
            ]
        );

        $response->assertRedirect(route('admin.news.index'));

        $news = News::where('title', 'Berita Baru')->firstOrFail();

        $this->assertNotNull($news->image_path);

        Storage::disk('public')->assertExists($news->image_path);
    }

    public function test_failed_database_insert_removes_uploaded_image(): void
    {
        $admin = $this->createAdmin();

        DB::unprepared("
            CREATE TRIGGER fail_news_insert
            BEFORE INSERT ON news
            BEGIN
                SELECT RAISE(ABORT, 'simulated database failure');
            END;
        ");

        try {
            $this->withoutExceptionHandling()
                ->actingAs($admin)
                ->post(
                    route('admin.news.store'),
                    [
                        'title' => 'Berita Gagal',
                        'content' => 'Konten berita gagal.',
                        'status' => 'draft',
                        'image' => UploadedFile::fake()->image('failed-image.jpg'),
                    ]
                );

            $this->fail('Penyimpanan database seharusnya gagal.');
        } catch (QueryException $e) {
            $this->assertStringContainsString(
                'simulated database failure',
                $e->getMessage()
            );
        }

        $this->assertDatabaseMissing('news', [
            'title' => 'Berita Gagal',
        ]);

        $this->assertCount(
            0,
            Storage::disk('public')->files('news')
        );
    }
    public function test_admin_can_delete_news_and_its_image(): void
    {
        $admin = $this->createAdmin();

        Storage::disk('public')->put(
            'news/old-image.jpg',
            'old image content'
        );

        $news = $this->createNews('news/old-image.jpg');

        $response = $this->actingAs($admin)->delete(
            route('admin.news.destroy', $news)
        );

        $response->assertRedirect(route('admin.news.index'));

        $this->assertDatabaseMissing('news', [
            'id' => $news->id,
        ]);

        Storage::disk('public')->assertMissing('news/old-image.jpg');
    }

    public function test_failed_database_delete_preserves_news_and_image(): void
    {
        $admin = $this->createAdmin();

        Storage::disk('public')->put(
            'news/old-image.jpg',
            'old image content'
        );

        $news = $this->createNews('news/old-image.jpg');

        DB::unprepared("
            CREATE TRIGGER fail_news_delete
            BEFORE DELETE ON news
            BEGIN
                SELECT RAISE(ABORT, 'simulated database failure');
            END;
        ");

        try {
            $this->withoutExceptionHandling()
                ->actingAs($admin)
                ->delete(route('admin.news.destroy', $news));

            $this->fail('Penghapusan database seharusnya gagal.');
        } catch (QueryException $e) {
            $this->assertStringContainsString(
                'simulated database failure',
                $e->getMessage()
            );
        }

        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'image_path' => 'news/old-image.jpg',
        ]);

        Storage::disk('public')->assertExists('news/old-image.jpg');
    }
    public function test_failed_image_deletion_is_logged_after_news_deleted(): void
    {
        $admin = $this->createAdmin();

        $news = $this->createNews('news/old-image.jpg');

        Storage::shouldReceive('disk')
            ->once()
            ->with('public')
            ->andReturn(
                \Mockery::mock(
                    \Illuminate\Contracts\Filesystem\Filesystem::class,
                    function ($mock) {
                        $mock->shouldReceive('delete')
                            ->once()
                            ->with('news/old-image.jpg')
                            ->andReturn(false);
                    }
                )
            );

        Log::spy();

        $response = $this->actingAs($admin)->delete(
            route('admin.news.destroy', $news)
        );

        $response->assertRedirect(route('admin.news.index'));

        $this->assertDatabaseMissing('news', [
            'id' => $news->id,
        ]);

        Log::shouldHaveReceived('warning')
            ->once()
            ->with(
                'Gagal menghapus gambar berita setelah berita dihapus.',
                [
                    'news_id' => $news->id,
                    'image_path' => 'news/old-image.jpg',
                ]
            );
    }
    public function test_failed_old_image_deletion_is_logged_after_news_updated(): void
    {
        $admin = $this->createAdmin();

        $oldImagePath = 'news/old-image.jpg';

        Storage::disk('public')->put(
            $oldImagePath,
            'old image content'
        );

        $news = $this->createNews($oldImagePath);

        $disk = Storage::disk('public');

        $mockDisk = \Mockery::mock(
            \Illuminate\Filesystem\FilesystemAdapter::class
        )->makePartial();

        $mockDisk->shouldReceive('putFileAs')
            ->andReturnUsing(
                fn (...$args) => $disk->putFileAs(...$args)
            );

        $mockDisk->shouldReceive('delete')
            ->once()
            ->with($oldImagePath)
            ->andReturn(false);

        Storage::set('public', $mockDisk);

                Log::spy();

                $response = $this->actingAs($admin)->put(
                    route('admin.news.update', $news),
                    [
                        'title' => 'Berita Diperbarui',
                        'content' => 'Konten berita diperbarui.',
                        'status' => 'draft',
                        'image' => UploadedFile::fake()->image('new-image.jpg'),
                    ]
                );

        $response->assertRedirect(route('admin.news.index'));

        $news->refresh();

        $this->assertNotEquals(
            $oldImagePath,
            $news->image_path
        );

        $this->assertNotEmpty($news->image_path);

        Log::shouldHaveReceived('warning')
            ->once()
            ->with(
                'Gagal menghapus gambar lama setelah berita diperbarui.',
                [
                    'news_id' => $news->id,
                    'image_path' => $oldImagePath,
                ]
            );
    }
}