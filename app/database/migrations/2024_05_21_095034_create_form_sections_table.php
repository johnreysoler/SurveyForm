<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_sections', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id');
            $table->boolean('is_question')->default(false);
            $table->longText('text')->nullable(); 
            $table->longText('html')->nullable(); 
            $table->integer('option_type_id')->nullable();  
            $table->boolean('is_required')->default(false);
            $table->boolean('is_multiple_select')->default(false);
            $table->boolean('with_other_option')->default(false);
            $table->text('option_vertical_align')->nullable();
            $table->text('image_position')->nullable();
            $table->boolean('prerequisite')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('form_sections');
    }
}
