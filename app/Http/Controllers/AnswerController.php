<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Enums\StatusEnum;

class AnswerController extends Controller
{
  public function show()
  {
    $data = Answer::orderBy('created_at', 'desc')->get();
    return response($data, 200);
  }

  public function getQuestionAnswers($question_id)
  {
    $data = Answer::where(['question_id' => $question_id])->get();
    return response($data, 200);
  }

  public function getUserAnswers($user_id)
  {
    $data = Answer::where(['answered_by' => $user_id])->get();
    return response($data, 200);
  }

  public function getSurveyAnswers($survey_id)
  {
    $data = Answer::where(['survey_id' => $survey_id])->get();
    return response($data, 200);
  }

  public function showSingle($id)
  {
    $data = Answer::where(['id' => $id])->first();
    return response($data, 200);
  }

  public function store(Request $request)
  {
    $request->validate([
      'answered_by' => 'required|integer|exists:users,id',
      'survey_id' => 'required|integer',
      'question_id' => 'required|integer',
      'answer' => 'required|string',
    ], [
      'answered_by.exists' => 'Sorry user id for answered by feild does not exist',
      'survey_id.required' => 'Survey id is required',
      'question_id.required' => 'Question id is required',
      'answer.required' => 'Answer is required',
    ]);

    $newAnswer = new Answer([
      'answered_by' => $request->answered_by,
      'survey_id' => $request->survey_id,
      'question_id' => $request->question_id,
      'answer' => $request->answer
    ]);
    $newAnswer->save();

    return response('answer created successfully', 201);
  }

  public function update($id, Request $request)
  {
    $request->validate([
      'survey_id' => 'integer',
      'question_id' => 'integer',
      'answer' => 'string',
      'answered_by' => 'integer|exists:users,id'
    ], [
      'answered_by.exists' => 'Sorry user id for answered by feild does not exist'
    ]);

    $answer = Answer::find($id);
    $answer->survey_id = $request->survey_id ? $request->survey_id : $answer->survey_id;
    $answer->question_id = $request->question_id ? $request->question_id : $answer->question_id;
    $answer->answer = $request->answer ? $request->answer : $answer->answer;
    $answer->answered_by = $request->answered_by ? $request->answered_by : $answer->answered_by;
    $answer->save();

    return response('answer updated successfully', 200);
  }

  public function delete($id)
  {
    $answer = Answer::find($id);
    $answer->delete();

    return response('answer deleted successfully', 200);
  }
}
