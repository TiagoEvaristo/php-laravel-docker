<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consultancies', function (Blueprint $table) {
            $table->id();
            $table->text('objetivo');
            $table->timestamp('data_reuniao')->default(now());
            $table->string('forma_contato');
            $table->string('status');
            $table->foreignId('consulting_report_id')->constrained()->onDelete('cascade');
            $table->foreignId('consultant_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultancies');
    }
};
