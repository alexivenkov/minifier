<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('original')->unique();
            $table->string('minified', 6)->nullable()->unique();
            $table->integer('transitions_count')->default(0);
            $table->integer('length');
            $table->timestamps();
        });

        DB::statement('CREATE sequence minified_sequence');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
        DB::statement('DROP sequence minified_sequence');
    }
}
