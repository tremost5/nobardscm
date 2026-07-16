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
        Schema::table('registrations', function (Blueprint $table) {
            $table->enum('wa_status', ['pending', 'sent', 'failed'])->default('pending')->after('ticket_token');
            $table->timestamp('wa_sent_at')->nullable()->after('wa_status');
            $table->text('wa_error')->nullable()->after('wa_sent_at');
            $table->unsignedInteger('wa_retry_count')->default(0)->after('wa_error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['wa_status', 'wa_sent_at', 'wa_error', 'wa_retry_count']);
        });
    }
};
