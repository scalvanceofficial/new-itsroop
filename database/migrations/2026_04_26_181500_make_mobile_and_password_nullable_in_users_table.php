<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY mobile VARCHAR(255) NULL');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY mobile VARCHAR(255) NOT NULL');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL');
    }
};
