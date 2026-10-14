<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        if(!Schema::hasColumn('identities','personal_information')) { Schema::table('identities',fn(Blueprint $t)=>$t->json('personal_information')->nullable()); }
    }
    public function down(): void
    {
        throw new RuntimeException('Conserve la información personal; prepare una reversión explícita antes de eliminarla.');
    }
};
