<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Enums\StatusEnum;

class SurveyController extends Controller
{
  public function show()
  {
    $data = Survey::where(['status' => StatusEnum::ACTIVE, 'deleted_at' => null])->orderBy('created_at', 'desc')->get();
        if ($data) {
            return response($data, 200);
        }
        abort(404);
    // return response($data, 200);
  }

  public function showSingle($id)
  {
    $data = Survey::where(['id' => $id, 'status' => StatusEnum::ACTIVE, 'deleted_at' => null])->first();
    return response($data? $data: 'Survey not found', $data? 200 : 404);
  }

  public function getSurveyWithQuestions($id)
  {
    $data = Survey::where(['id' => $id, 'status' => StatusEnum::ACTIVE, 'deleted_at' => null])->with('questions')->first();
    return response($data, 200);
  }

  public function store(Request $request)
  {
    $request->validate([
      'created_by' => 'required|integer|exists:users,id',
      'title' => 'required|string|max:100'
    ], [
      'title.required' => 'Survey title is required',
      'created_by.exists' => 'Sorry user id for created by feild does not exist'
    ]);

    $newSurvey = new Survey([
      'title' => $request->title,
      'created_by' => $request->created_by,
      'status' => StatusEnum::ACTIVE
    ]);
    $newSurvey->save();

    return response('survey created successfully', 201);
  }

  public function update($id, Request $request)
  {
    $request->validate([
      'title' => 'required|string|max:100',
      'status' => 'in:active,inactive'
    ], [
      'status.in' => 'Invalid status <required: active or inactive>'
    ]);

    $survey = Survey::find($id);
    $survey->title = $request->title;
    $survey->status = $request->status ? $request->status : $survey->status;
    $survey->save();

    return response('survey updated successfully', 200);
  }

  public function delete($id)
  {
    $survey = Survey::find($id);
    $survey->delete();

    return response('survey deleted successfully', 200);
  }
}
