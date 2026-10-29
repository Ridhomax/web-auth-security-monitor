<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\News;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_student_cannot_access_admin_dashboard(): void
    {
        $student = $this->createTestUser('student');

        $response = $this->actingAs($student)
            ->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_student_cannot_access_news_management(): void
    {
        $student = $this->createTestUser('student');

        $response = $this->actingAs($student)
            ->get('/admin/news');

        $response->assertForbidden();
    }

    public function test_student_cannot_access_document_management(): void
    {
        $student = $this->createTestUser('student');

        $response = $this->actingAs($student)
            ->get('/admin/documents');

        $response->assertForbidden();
    }

    public function test_student_cannot_access_security_dashboard(): void
    {
        $student = $this->createTestUser('student');

        $response = $this->actingAs($student)
            ->get('/admin/security-dashboard');

        $response->assertForbidden();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createTestUser('admin');

        $response = $this->actingAs($admin)
            ->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_student_cannot_create_news(): void
{
    $student = $this->createTestUser('student');

    $response = $this->actingAs($student)
        ->post('/admin/news', [
            'title' => 'Berita Pengujian',
            'content' => 'Konten pengujian keamanan.',
            'status' => 'draft',
        ]);

    $response->assertForbidden();
}

public function test_student_cannot_create_document(): void
{
    $student = $this->createTestUser('student');

    $response = $this->actingAs($student)
        ->post('/admin/documents', [
            'title' => 'Dokumen Pengujian',
            'category' => 'Pengujian',
        ]);

    $response->assertForbidden();
}

public function test_student_cannot_access_news_creation_page(): void
{
    $student = $this->createTestUser('student');

    $response = $this->actingAs($student)
        ->get('/admin/news/create');

    $response->assertForbidden();
}

public function test_student_cannot_access_document_creation_page(): void
{
    $student = $this->createTestUser('student');

    $response = $this->actingAs($student)
        ->get('/admin/documents/create');

    $response->assertForbidden();
}

public function test_student_cannot_update_news(): void
{
    $student = $this->createTestUser('student');

    $news = News::create([
        'title' => 'Berita Asli',
        'slug' => 'berita-asli',
        'excerpt' => 'Ringkasan berita asli',
        'content' => 'Konten berita asli.',
        'status' => 'draft',
    ]);

    $response = $this->actingAs($student)
        ->put('/admin/news/' . $news->id, [
            'title' => 'Berita Diubah',
            'content' => 'Konten perubahan.',
            'status' => 'draft',
        ]);

    $response->assertForbidden();

    $this->assertDatabaseHas('news', [
        'id' => $news->id,
        'title' => 'Berita Asli',
    ]);
}

public function test_student_cannot_delete_news(): void
{
    $student = $this->createTestUser('student');

    $news = News::create([
        'title' => 'Berita Untuk Dihapus',
        'slug' => 'berita-untuk-dihapus',
        'excerpt' => 'Ringkasan berita',
        'content' => 'Konten berita.',
        'status' => 'draft',
    ]);

    $response = $this->actingAs($student)
        ->delete('/admin/news/' . $news->id);

    $response->assertForbidden();

    $this->assertDatabaseHas('news', [
        'id' => $news->id,
        'title' => 'Berita Untuk Dihapus',
    ]);
}

public function test_student_cannot_update_document(): void
{
    $student = $this->createTestUser('student');

    $document = Document::create([
        'title' => 'Dokumen Asli',
        'slug' => 'dokumen-asli',
        'category' => 'Pengujian',
        'description' => 'Dokumen untuk pengujian keamanan.',
        'file_path' => 'documents/test-document.pdf',
        'original_filename' => 'test-document.pdf',
        'is_active' => true,
        'download_count' => 0,
    ]);

    $response = $this->actingAs($student)
        ->put('/admin/documents/' . $document->id, [
            'title' => 'Dokumen Diubah',
            'category' => 'Pengujian',
        ]);

    $response->assertForbidden();

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'title' => 'Dokumen Asli',
    ]);
}

public function test_student_cannot_delete_document(): void
{
    $student = $this->createTestUser('student');

    $document = Document::create([
        'title' => 'Dokumen Untuk Dihapus',
        'slug' => 'dokumen-untuk-dihapus',
        'category' => 'Pengujian',
        'description' => 'Dokumen untuk pengujian penghapusan.',
        'file_path' => 'documents/test-delete.pdf',
        'original_filename' => 'test-delete.pdf',
        'is_active' => true,
        'download_count' => 0,
    ]);

    $response = $this->actingAs($student)
        ->delete('/admin/documents/' . $document->id);

    $response->assertForbidden();

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'title' => 'Dokumen Untuk Dihapus',
    ]);
}

    private function createTestUser(string $role): User
    {
        return User::create([
            'name' => 'Test ' . ucfirst($role),
            'email' => $role . '-authorization@example.com',
            'password' => bcrypt('PasswordTest123!'),
            'role' => $role,
            'is_active' => true,
        ]);
    }
}