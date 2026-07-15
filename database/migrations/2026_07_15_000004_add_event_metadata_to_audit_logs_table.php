<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('event_type')->nullable()->after('method');
            $table->string('action')->nullable()->after('event_type');
            $table->text('description')->nullable()->after('action');
            $table->json('metadata')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['event_type', 'action', 'description', 'metadata']);
        });
    }
};
