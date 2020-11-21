<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->integer('category_id')->unsigned(); 
            // $table->foreign('category_id')->references('id')->on('categories');

            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
