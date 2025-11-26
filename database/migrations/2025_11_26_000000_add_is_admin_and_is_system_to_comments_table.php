<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'is_admin')) {
                $table->boolean('is_admin')->default(false);
            }

            if (!Schema::hasColumn('comments', 'is_system')) {
                $table->boolean('is_system')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'is_admin')) {
                $table->dropColumn('is_admin');
            }

            if (Schema::hasColumn('comments', 'is_system')) {
                $table->dropColumn('is_system');
            }
        });
    }
};
