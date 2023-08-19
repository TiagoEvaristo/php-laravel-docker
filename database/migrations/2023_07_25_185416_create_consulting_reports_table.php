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
        Schema::create('consulting_reports', function (Blueprint $table) {
            $table->id();
            $table->text('relatorio');
            $table->decimal('meta_corte', $precision=10, $scale=2)->nullable();
            $table->decimal('valor_estimado_ganho', $precision=10, $scale=2)->nullable();
            $table->text('contas_corte')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('consultant_id')->nullable();
            $table->foreign('consultant_id')->references('id')->on('consultants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulting_reports');
    }
};
