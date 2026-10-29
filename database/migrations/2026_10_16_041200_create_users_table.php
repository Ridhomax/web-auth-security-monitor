
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel hanya jika belum tersedia.
        // Tabel users di MySQL yang sudah ada tidak akan diubah.
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('email', 150)->unique();
                $table->string('password');
                $table->string('role', 40)->default('student');
                $table->boolean('is_active')->default(true);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            });
        }
    }

    public function down(): void
    {
        // Sengaja tidak menghapus tabel users
        // untuk melindungi data pengguna yang sudah ada.
    }
};
