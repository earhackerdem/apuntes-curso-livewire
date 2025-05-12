<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() === 'sqlite') {
            // Para SQLite, recrear la tabla con la nueva columna
            Schema::table('posts', function (Blueprint $table) {
                $table->text('content')->change();
            });
        } else {
            // Para MySQL, usar ALTER TABLE
            DB::statement('ALTER TABLE posts MODIFY content TEXT');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::getDriverName() === 'sqlite') {
            // Para SQLite, recrear la tabla con la nueva columna
            Schema::table('posts', function (Blueprint $table) {
                $table->string('content')->change();
            });
        } else {
            // Para MySQL, usar ALTER TABLE
            DB::statement('ALTER TABLE posts MODIFY content VARCHAR(255)');
        }
    }
};
