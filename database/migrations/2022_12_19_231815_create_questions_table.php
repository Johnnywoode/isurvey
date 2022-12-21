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
    Schema::create('questions', function (Blueprint $table) {
      $table->id();
      $table->integer('survey_id')->comment('id of the survey for which the question belongs to');
      $table->string('question', 500)->comment('question that requires an answer');
      $table->text('options')->nullable()->comment('answer options for question. ie. if multiple choice');
      $table->string('type', 100)->comment('type of question. can have the following types: [text, single_choice, multiple_choice, number]');
      $table->string('status', 20)->default('active')->comment('survey status has the following states:
          ACTIVE - when question is available for all users.
          INACTIVE -  when question gets flagged or taken off the public space and is inaccessible to public users.');
      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('questions');
  }
};
