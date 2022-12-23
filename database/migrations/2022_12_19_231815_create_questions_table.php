<?php

use App\Enums\QuestionType;
use App\Enums\StatusEnum;
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
      $table->unsignedBigInteger('survey_id')->comment('id of the survey for which the question belongs to');
      $table->string('question', 500)->comment('question that requires an answer');
      $table->text('options')->nullable()->comment('answer options for question. ie. if multiple choice');
      $table->enum('type', QuestionType::values())->comment('type of question. can have the following types: [text, single_choice, multiple_choice, number]');
      $table->enum('status', StatusEnum::values())->default('active')->comment('survey status has the following states:
        ACTIVE - when survey is available for all users.
        INACTIVE -  when survey gets flagged or taken off the public space and is inaccessible to public users.');
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
