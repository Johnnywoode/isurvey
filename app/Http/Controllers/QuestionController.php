<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Enums\StatusEnum;

class QuestionController extends Controller
{
  public function show()
  {
    $data = Question::where(['status' => StatusEnum::ACTIVE])->orderBy('created_at', 'desc')->get();
    return response($data, 200);
  }

  public function getQuestionWithAnswers($question_id)
  {
    $data = Question::where(['id' => $question_id, 'status' => StatusEnum::ACTIVE])->with('answers')->first();
    return response($data, 200);
  }

  public function showSingle($id)
  {
    $data = Question::where(['id' => $id, 'status' => StatusEnum::ACTIVE])->with('survey')->first();
    return response($data, 200);
  }

  public function store(Request $request)
  {
    $request->validate([
      'survey_id' => 'required|integer',
      'question' => 'required|string|max:500',
      'type' => 'required|in:text,single_choice,multiple_choice,number'
    ], [
      'survey_id.required' => 'Survey id  is required',
      'question.required' => 'Question title is required',
      'type.required' => 'Question type is required',
      'type.in' => 'Question type is invalid <required: text,single_choice,multiple_choice or number>',
    ]);

    $newQuestion = new Question([
      'survey_id' => $request->survey_id,
      'question' => $request->question,
      'type' => $request->type,
      'options' => $request->options? $request->options : null,
      'status' => StatusEnum::ACTIVE
    ]);
    $newQuestion->save();

    return response('question created successfully', 201);
  }

  public function update($id, Request $request)
  {
    $request->validate([
      'survey_id' => 'integer',
      'question' => 'string|max:500',
      'type' => 'in:text,single_choice,multiple_choice,number'
    ], [
      'type.in' => 'Invalid status <required: text,single_choice,multiple_choice or number>'
    ]);

    $question = Question::find($id);
    $question->question_id = $request->question_id ? $request->question_id : $question->question_id;
    $question->question = $request->question ? $request->question : $question->question;
    $question->type = $request->type ? $request->type : $question->type;
    $question->save();

    return response('question updated successfully', 200);
  }

  public function delete($id)
  {
    $question = Question::find($id);
    $question->delete();

    return response('question deleted successfully', 200);
  }
}
