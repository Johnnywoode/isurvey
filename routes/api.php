<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SurveyController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function(){
  Route::prefix('survey')->group(function () {
    Route::post('/', [SurveyController::class, 'store']);
    Route::get('/', [SurveyController::class, 'show']);
    Route::get('/with-creator', [SurveyController::class, 'getSurveysWithCreators']);
    Route::get('with-questions-and-answers/', [SurveyController::class, 'getSurveysWithQuestionAndAnswers']);
    Route::get('/{id}', [SurveyController::class, 'showSingle']);
    Route::get('/with-creator/{id}', [SurveyController::class, 'getSurveyWithCreator']);
    Route::get('/with-questions/{id}', [SurveyController::class, 'getSurveyWithQuestions']);
    Route::put('/{id}', [SurveyController::class, 'update']);
    Route::delete('/{id}', [SurveyController::class, 'delete']);
  });

  Route::prefix('question')->group(function () {
    Route::post('/', [QuestionController::class, 'store']);
    Route::get('/', [QuestionController::class, 'show']);
    Route::get('/{id}', [QuestionController::class, 'showSingle']);
    Route::get('/with-answers/{id}', [QuestionController::class, 'getQuestionWithAnswers']);
    Route::get('/survey-questions-with-answers/{id}', [QuestionController::class, 'getSurveyQuestionsWithAnswers']);
    Route::put('/{id}', [QuestionController::class, 'update']);
    Route::delete('/{id}', [QuestionController::class, 'delete']);
  });

  Route::prefix('answer')->group(function () {
    Route::post('/', [AnswerController::class, 'store']);
    Route::get('/', [AnswerController::class, 'show']);
    Route::get('/{id}', [AnswerController::class, 'showSingle']);
    Route::put('/{id}', [QuestionController::class, 'update']);
    Route::delete('/{id}', [QuestionController::class, 'delete']);
    Route::get('with-question/', [AnswerController::class, 'getAnswersWithQuestions']);
    Route::get('/with-question/{id}', [AnswerController::class, 'getAnswerWithQuestion']);
    // Route::get('/with-question-and-survey', [AnswerController::class, 'getAnswersWithQuestionsAndSurveys']);
    // Route::get('/with-question-and-survey/{id}', [AnswerController::class, 'getAnswerWithQuestionAndSurvey']);
    Route::get('/user-answers/{id}', [AnswerController::class, 'getUserAnswers']);
    Route::get('/survey-answers/{id}', [AnswerController::class, 'getSurveyAnswers']);
  });
});
