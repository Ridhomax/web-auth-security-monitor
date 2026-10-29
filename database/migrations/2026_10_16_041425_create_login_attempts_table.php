
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
                ->nullable();

            $table->foreign('user_id', 'fk_login_attempt_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->string('attempted_identifier', 150);
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->boolean('is_successful')->default(false);
            $table->string('failure_reason', 100)->nullable();
            $table->timestamp('attempted_at')->useCurrent();

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
