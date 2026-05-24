<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Corrige tablas de análisis de lote mal generadas o con nombres de columnas distintos al código.
     */
    public function up(): void
    {
        if (Schema::hasTable('batch_analysis_state')) {
            if (Schema::hasColumn('batch_analysis_state', 'datetime') && ! Schema::hasColumn('batch_analysis_state', 'dateto')) {
                DB::statement('ALTER TABLE `batch_analysis_state` CHANGE `datetime` `dateto` DATETIME NULL');
            }

            if (Schema::hasColumn('batch_analysis_state', 'datediff') && ! Schema::hasColumn('batch_analysis_state', 'datediif')) {
                DB::statement('ALTER TABLE `batch_analysis_state` CHANGE `datediff` `datediif` DECIMAL(8,2) NULL');
            }

            if (! Schema::hasColumn('batch_analysis_state', 'updatedby')) {
                Schema::table('batch_analysis_state', function (Blueprint $table) {
                    $table->integer('updatedby')->nullable()->after('updated');
                });
            }
        }

        if (Schema::hasTable('batch_analysis') && ! Schema::hasColumn('batch_analysis', 'updatedby')) {
            Schema::table('batch_analysis', function (Blueprint $table) {
                $table->integer('updatedby')->nullable()->after('updated');
            });
        }

        $lineIsWrong = Schema::hasTable('batch_analysis_line')
            && Schema::hasColumn('batch_analysis_line', 'quality_parameter_id')
            && ! Schema::hasColumn('batch_analysis_line', 'batch_analysis_id');

        if ($lineIsWrong) {
            Schema::rename('batch_analysis_line', 'batch_analysis_line_legacy_wrong_schema');

            Schema::create('batch_analysis_line', function (Blueprint $table) {
                $table->integer('batch_analysis_line_id', true, true);
                $table->dateTime('created');
                $table->integer('createdby');
                $table->dateTime('updated')->nullable();
                $table->integer('updatedby')->nullable();
                $table->enum('isactive', ['Y', 'N']);
                $table->decimal('moisture', 8, 2);
                $table->decimal('temperature', 8, 2);
                $table->decimal('sodium', 8, 2);
                $table->decimal('protein', 8, 2);
                $table->integer('number_batch');
                $table->integer('batch_analysis_id');
                $table->primary('batch_analysis_line_id');
                $table->foreign('batch_analysis_id')->references('batch_analysis_id')->on('batch_analysis')->onDelete('restrict')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('batch_analysis') && Schema::hasColumn('batch_analysis', 'updatedby')) {
            Schema::table('batch_analysis', function (Blueprint $table) {
                $table->dropColumn('updatedby');
            });
        }

        if (Schema::hasTable('batch_analysis_state') && Schema::hasColumn('batch_analysis_state', 'updatedby')) {
            Schema::table('batch_analysis_state', function (Blueprint $table) {
                $table->dropColumn('updatedby');
            });
        }
    }
};
