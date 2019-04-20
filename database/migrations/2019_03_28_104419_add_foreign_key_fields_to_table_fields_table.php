<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyFieldsToTableFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table_fields', function (Blueprint $table) {
            $table->string('table')->nullable()->after('default');
            $table->string('foreign_key')->nullable()->after('table');
            $table->string('onDelete')->nullable()->after('foreign_key');
            $table->string('onUpdate')->nullable()->after('onDelete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_fields', function (Blueprint $table) {
            $table->dropColumn(['table', 'foreign_key', 'onDelete', 'onUpdate']);
        });
    }
}
