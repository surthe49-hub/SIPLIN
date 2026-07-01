<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cleanup: Drop dead columns and tables from disabled features.
 *
 * Context: SIPLIN was forked from SIBARAKU template which had:
 * - Referral code system (kode referral untuk registrasi)
 * - Security questions (untuk self-service password reset)
 * - Birth date verification (untuk password reset)
 *
 * All three features were disabled in PLN ULP Cilacap version because:
 * - No public registration (admin creates all users)
 * - Password reset now handled by admin manually (not self-service)
 * - Security question flow depended on features that don't exist
 *
 * This migration removes the dead schema so future development is not confused
 * by unused columns and tables.
 *
 * SAFETY:
 * - Uses hasColumn() / hasTable() checks so it's idempotent
 * - down() method fully reversible (recreates original schema)
 * - Drops FK on referred_by BEFORE dropping the column (avoid FK error)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop FK dari referred_by ke referral_codes SEBELUM drop apapun
        // (Kalau FK tidak di-drop dulu, drop kolom akan error)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'referred_by')) {
                // FK name convention di Laravel: {table}_{column}_foreign
                try {
                    $table->dropForeign(['referred_by']);
                } catch (\Throwable $e) {
                    // FK mungkin tidak ada atau nama beda - lanjut saja
                }
            }
        });

        // Step 2: Drop kolom dead dari users
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            foreach ([
                'birth_date',
                'referral_code',
                'referred_by',
                'security_question_1',
                'security_answer_1',
                'security_question_2',
                'security_answer_2',
                'custom_security_question',
                'custom_security_answer',
                'security_setup_completed',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        // Step 3: Drop tabel referral
        Schema::dropIfExists('referral_code_usage');
        Schema::dropIfExists('referral_codes');
    }

    /**
     * Reverse the migrations.
     *
     * Kalau perlu rollback, recreate schema sesuai kondisi SEBELUM migration ini.
     */
    public function down(): void
    {
        // Recreate referral_codes table
        if (!Schema::hasTable('referral_codes')) {
            Schema::create('referral_codes', function (Blueprint $table) {
                $table->id();
                $table->string('code', 20)->unique();
                $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
                $table->integer('max_usage')->default(0);
                $table->integer('usage_count')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        // Recreate referral_code_usage table
        if (!Schema::hasTable('referral_code_usage')) {
            Schema::create('referral_code_usage', function (Blueprint $table) {
                $table->id();
                $table->foreignId('referral_code_id')->constrained('referral_codes')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamp('used_at');
                $table->timestamps();
            });
        }

        // Recreate dead columns di users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 20)->after('role');
            }
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->foreignId('referred_by')->nullable()->after('referral_code')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'security_question_1')) {
                $table->unsignedTinyInteger('security_question_1')->nullable()->after('referred_by');
            }
            if (!Schema::hasColumn('users', 'security_answer_1')) {
                $table->string('security_answer_1')->nullable()->after('security_question_1');
            }
            if (!Schema::hasColumn('users', 'security_question_2')) {
                $table->unsignedTinyInteger('security_question_2')->nullable()->after('security_answer_1');
            }
            if (!Schema::hasColumn('users', 'security_answer_2')) {
                $table->string('security_answer_2')->nullable()->after('security_question_2');
            }
            if (!Schema::hasColumn('users', 'custom_security_question')) {
                $table->string('custom_security_question')->nullable()->after('security_answer_2');
            }
            if (!Schema::hasColumn('users', 'custom_security_answer')) {
                $table->string('custom_security_answer')->nullable()->after('custom_security_question');
            }
            if (!Schema::hasColumn('users', 'security_setup_completed')) {
                $table->boolean('security_setup_completed')->default(false)->after('custom_security_answer');
            }
        });
    }
};