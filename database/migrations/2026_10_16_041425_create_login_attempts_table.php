
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('attempted_identifier');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->boolean('is_successful')->default(false);
            $table->string('failure_reason')->nullable();
            $table->timestamp('attempted_at');

            $table->index(
                ['attempted_identifier', 'attempted_at'],
                'idx_login_identifier_time'
            );

            $table->index(
                ['ip_address', 'attempted_at'],
                'idx_login_ip_time'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
