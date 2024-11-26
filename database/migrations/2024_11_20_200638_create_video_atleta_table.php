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
        Schema::create('video_atleta', function (Blueprint $table) {
            $table->uuid('video_id')->index();
            $table->foreign('video_id')
                ->references('id')
                ->on('videos');
            $table->uuid('atleta_id')->index();
            $table->foreign('atleta_id')
                ->references('id')
                ->on('atletas');
            $table->unique(['video_id', 'atleta_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atleta_video');
    }
};
