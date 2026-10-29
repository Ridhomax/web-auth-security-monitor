
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detection_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_name', 100);
            $table->string('detection_scope', 20);
            $table->unsignedInteger('threshold_count');
            $table->unsignedInteger('window_seconds');
            $table->boolean('is_enabled')->default(true);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detection_rules');
    }
};
