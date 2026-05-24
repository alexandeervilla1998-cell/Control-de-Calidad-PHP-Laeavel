<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Alinea el tipo de batch_analysis_id con batch_analysis.batch_analysis_id (signed INT) para permitir la FK.
     */
    public function up(): void
    {
        if (! Schema::hasTable('batch_analysis_line') || ! Schema::hasColumn('batch_analysis_line', 'batch_analysis_id')) {
            return;
        }

        DB::statement('ALTER TABLE `batch_analysis_line` MODIFY `batch_analysis_id` INT(11) NOT NULL');

        $fkName = 'batch_analysis_line_batch_analysis_id_foreign';
        $count = DB::selectOne(
            'SELECT COUNT(*) AS c FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?',
            ['batch_analysis_line', $fkName]
        );

        if ((int) $count->c === 0) {
            Schema::table('batch_analysis_line', function (Blueprint $table) use ($fkName) {
                $table->foreign('batch_analysis_id', $fkName)
                    ->references('batch_analysis_id')
                    ->on('batch_analysis')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('batch_analysis_line')) {
            return;
        }

        Schema::table('batch_analysis_line', function (Blueprint $table) {
            $table->dropForeign(['batch_analysis_id']);
        });
    }
};
