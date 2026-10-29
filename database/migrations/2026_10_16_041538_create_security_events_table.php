
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
                ->nullable();

            $table->foreign('login_attempt_id', 'fk_event_login_attempt')
                ->references('id')
                ->on('login_attempts')
                ->nullOnDelete();

            $table->string('event_type', 100);
            $table->string('severity', 20)->default('medium');
            $table->text('description')->nullable();
            $table->string('status', 30)->default('open');
            $table->timestamp('detected_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();

            $table->index(
                ['severity', 'detected_at'],
                'idx_event_severity_time'
            );

            $table->index(
                ['status', 'detected_at'],
                'idx_event_status_time'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_events');
    }
};
