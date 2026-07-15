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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('email');
            $table->string('phone')->nullable()->after('username');
            $table->boolean('force_password_change')->default(false)->after('status');
            $table->timestamp('password_sent_at')->nullable()->after('force_password_change');
            $table->timestamp('last_login_at')->nullable()->after('password_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'phone', 'force_password_change', 'password_sent_at', 'last_login_at']);
        });
    }
};
