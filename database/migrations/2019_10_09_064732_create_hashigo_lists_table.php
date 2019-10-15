<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHashigoListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hashigo_lists', function (Blueprint $table) {
            $table->increments('member_id');
            // $table->increments('id');
            $table->string('first_store_id',255);
            $table->string('second_store_id',255);
            $table->string('third_store_id',255)->default("");
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hashigo_lists');
    }
}
