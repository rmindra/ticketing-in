<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Update enum status untuk tambah status baru
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Closed', 'Reopened'])->default('Open')->change();

            // Tambah kolom untuk track konfirmasi
            $table->boolean('user_confirmed')->default(false);
            $table->boolean('admin_confirmed')->default(false);
            $table->timestamp('resolved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->enum('status', ['Open', 'In Progress', 'Closed'])->default('Open')->change();
            $table->dropColumn(['user_confirmed', 'admin_confirmed', 'resolved_at']);
        });
    }
};
