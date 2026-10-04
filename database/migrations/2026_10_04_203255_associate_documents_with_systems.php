<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('system_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->nullOnDelete();
        });

        $jfrvSystemId = DB::table('systems')
            ->where('id', 1)
            ->value('id');

        if ($jfrvSystemId !== null) {
            DB::table('documents')
                ->where('user_id', 1)
                ->whereNull('system_id')
                ->update(['system_id' => $jfrvSystemId]);
        }
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('system_id');
        });
    }
};
