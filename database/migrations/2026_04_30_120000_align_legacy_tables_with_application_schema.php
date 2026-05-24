<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajusta tablas creadas manualmente o con esquema antiguo para coincidir con modelos y vistas actuales.
     */
    public function up(): void
    {
        if (Schema::hasTable('product') && ! Schema::hasColumn('product', 'updatedby')) {
            Schema::table('product', function (Blueprint $table) {
                $table->integer('updatedby')->nullable()->after('updated');
            });
        }

        if (Schema::hasTable('lot') && ! Schema::hasColumn('lot', 'updatedby')) {
            Schema::table('lot', function (Blueprint $table) {
                $table->integer('updatedby')->nullable()->after('updated');
            });
        }

        if (! Schema::hasTable('quality_parameters')) {
            return;
        }

        $hasLegacyId = Schema::hasColumn('quality_parameters', 'id');
        $hasAppPk = Schema::hasColumn('quality_parameters', 'quality_parameter_id');
        $hasMetrics = Schema::hasColumn('quality_parameters', 'min_moisture');

        if ($hasLegacyId && ! $hasAppPk && ! $hasMetrics) {
            Schema::table('quality_parameters', function (Blueprint $table) {
                $table->decimal('min_moisture', 8, 2)->default(0)->after('product_id');
                $table->decimal('max_moisture', 8, 2)->default(0);
                $table->decimal('min_temperature', 8, 2)->default(0);
                $table->decimal('max_temperature', 8, 2)->default(0);
                $table->decimal('min_sodium', 8, 2)->default(0);
                $table->decimal('max_sodium', 8, 2)->default(0);
                $table->decimal('min_protein', 8, 2)->default(0);
                $table->decimal('max_protein', 8, 2)->default(0);
            });

            DB::statement('ALTER TABLE `quality_parameters` CHANGE `id` `quality_parameter_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

            if (Schema::hasColumn('quality_parameters', 'name')) {
                Schema::table('quality_parameters', function (Blueprint $table) {
                    $table->dropColumn(['name', 'description']);
                });
            }

            if (Schema::hasColumn('quality_parameters', 'created_at')) {
                Schema::table('quality_parameters', function (Blueprint $table) {
                    $table->dropColumn(['created_at', 'updated_at']);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product') && Schema::hasColumn('product', 'updatedby')) {
            Schema::table('product', function (Blueprint $table) {
                $table->dropColumn('updatedby');
            });
        }

        if (Schema::hasTable('lot') && Schema::hasColumn('lot', 'updatedby')) {
            Schema::table('lot', function (Blueprint $table) {
                $table->dropColumn('updatedby');
            });
        }
    }
};
