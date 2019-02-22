<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCommoLogsTable
 * Communication Logs will track the dispatch and
 * response of Order Messages to the child managers
 */

class CreateCommoLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commo_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('message_identifier')->nullable()->default('');
            $table->string('receiver')->nullable()->default(null);
            $table->string('sender')->nullable()->default(null);
            $table->mediumText('message')->nullable();
            $table->string('type')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commo_logs');
    }
}
