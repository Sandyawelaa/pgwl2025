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
        // Pastikan tabel sudah ada sebelum menambahkan kolom
        if (Schema::hasTable('points')) {
            Schema::table('points', function (Blueprint $table) {
                if (!Schema::hasColumn('points', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->foreign('user_id')->references('id')->on('users');
                }
            });
        }

        if (Schema::hasTable('polylines')) {
            Schema::table('polylines', function (Blueprint $table) {
                if (!Schema::hasColumn('polylines', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->foreign('user_id')->references('id')->on('users');
                }
            });
        }

        if (Schema::hasTable('polygon')) {
            Schema::table('polygon', function (Blueprint $table) {
                if (!Schema::hasColumn('polygon', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->foreign('user_id')->references('id')->on('users');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('points')) {
            Schema::table('points', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasTable('polylines')) {
            Schema::table('polylines', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasTable('polygon')) {
            Schema::table('polygon', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
