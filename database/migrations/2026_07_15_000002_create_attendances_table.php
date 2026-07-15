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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('checked_in_at')->nullable();
            $table->enum('checkin_method', ['qr', 'manual'])->default('qr');
            $table->string('ip_address')->nullable();
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->timestamps();

            $table->unique(['registration_id', 'checked_in_by', 'checked_in_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
