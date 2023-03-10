<?php

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
		Schema::create('surveys', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('created_by')->comment('id of the user who created the survey');
			$table->string('title', 100)->comment('title of the survey');
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
		Schema::dropIfExists('surveys');
	}
};
