<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dtc_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obd_record_id')->constrained()->cascadeOnDelete();
            $table->string('code', 6);
            $table->timestamps();

            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dtc_codes');
    }
};
