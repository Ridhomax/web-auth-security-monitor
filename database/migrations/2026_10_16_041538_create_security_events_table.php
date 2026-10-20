
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('login_attempt_id')
                ->constrained('login_attempts')
                ->cascadeOnDelete();

            $table->string('event_type', 100);
            $table->string('severity', 20);
            $table->text('description')->nullable();
            $table->string('status', 30)->default('open');
            $table->timestamp('detected_at');
            $table->timestamp('resolved_at')->nullable();

            $table->index(
                ['event_type', 'status', 'detected_at'],
                'idx_security_event_type_status_time'
            );

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_events');
    }
};
