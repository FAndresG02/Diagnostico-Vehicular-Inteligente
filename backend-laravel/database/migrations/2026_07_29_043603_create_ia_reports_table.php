<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ia_reports', function (Blueprint $table) {
            $table->id();
            $table->string('dtc_code', 6);
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->longText('report');
            $table->timestamps();

            $table->index('dtc_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ia_reports');
    }
};
