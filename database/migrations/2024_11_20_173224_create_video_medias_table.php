<?php

use core\domain\enum\MediaStatus;
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
        Schema::create('video_medias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('video_id')->unique();
            $table->foreign('video_id')->references('id')->on('videos');
            $table->string('file_path');
            $table->string('encoded_path')->nullable();
            $table->enum(
                'media_status',
                array_map(
                    fn($mediaStatus) => $mediaStatus->value, MediaStatus::cases()
                )
            )->default(MediaStatus::PENDING->value);
            // $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_medias');
    }
};
