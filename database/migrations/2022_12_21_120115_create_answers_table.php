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
    Schema::create('answers', function (Blueprint $table) {
      $table->id();
      $table->integer('answered_by')->comment('id of the user for which the answer belongs to');
      $table->integer('survey_id')->comment('id of the survey for which the question being answered belongs to');
      $table->integer('question_id')->comment('id of the question for which the answer belongs to');
      $table->text('answer')->comment('answer to question');
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
    Schema::dropIfExists('answers');
  }
};
