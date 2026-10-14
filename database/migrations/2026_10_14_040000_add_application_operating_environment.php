<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('system_id')->nullable()->constrained()->restrictOnDelete();
            $table->json('compatible_platforms')->nullable();
            $table->text('execution_requirements')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['system_id']);
            $table->dropColumn(['system_id', 'compatible_platforms', 'execution_requirements']);
        });
    }
};
