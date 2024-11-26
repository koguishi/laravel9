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
        Schema::create('video_categoria', function (Blueprint $table) {
            $table->uuid('video_id')->index();
            $table->foreign('video_id')
                ->references('id')
                ->on('videos');
                $table->uuid('categoria_id')->index();
                $table->foreign('categoria_id')
                    ->references('id')
                    ->on('categorias');
                $table->unique(['video_id', 'categoria_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoria_video');
    }
};
