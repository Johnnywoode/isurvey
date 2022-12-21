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
    return response($data, 200);
  }

  public function showSingle($id)
  {
    $data = Survey::where(['id' => $id, 'status' => StatusEnum::ACTIVE, 'deleted_at' => null])->with('questions')->first();
    return response($data, 200);
  }

  public function store(Request $request)
  {
    $user = auth()->user();
    $request->validate([
      'title' => 'required|string|max:100'
    ], [
      'title.required' => 'Survey title is required'
    ]);

    $newSurvey = new Survey([
      'title' => $request->title,
      'created_by' => $user->id,
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
