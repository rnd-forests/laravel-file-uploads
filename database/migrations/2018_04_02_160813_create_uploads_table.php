<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->uuid('hashed_name')->unique();
            $table->unsignedInteger('owner_id')->index()->nullable();
            $table->unsignedInteger('destination_id')->nullable();
            $table->string('destination_type')->nullable();
            $table->string('size');
            $table->string('type');
            $table->string('mime')->nullable();
            $table->string('path');
            $table->string('extension');
            $table->timestamps();

            $table->index(['destination_id', 'destination_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
