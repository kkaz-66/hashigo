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
            $table->integer('member_id')->unsigned();
            $table->integer('id')->autoIncrement();
            $table->string('first_store_id',255);
            $table->string('second_store_id',255);
            $table->string('third_store_id',255)->default("");
            // $table->timestamp('created_at');
            // $table->timestamp('updated_at');
            // update文実行された際に上書きされてしまうため
            // created_at,updated_atを作成した
            $table->timestamps();

            $table->unique(['member_id', 'id']);
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
