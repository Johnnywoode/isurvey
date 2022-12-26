<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Enums\StatusEnum;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Response;

class SurveyController extends Controller
{
    public function show()
    {
        $surveys = Survey::where(['status' => StatusEnum::ACTIVE])->orderBy('created_at', 'desc')->get();
        if ($surveys) {
            return count($surveys) < 1
                ? response(['message' => 'No surveys found', 'data' => $surveys], Response::HTTP_NO_CONTENT)
                : response(['message' => 'Surveys found', 'data' => $surveys], Response::HTTP_OK);
        }
        return  response(['message' => 'Some database error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getSurveysWithCreators()
    {
        $surveys = Survey::where(['status' => StatusEnum::ACTIVE])->with('creator')->orderBy('created_at', 'desc')->get();
        if ($surveys) {
            return count($surveys) < 1
                ? response(['message' => 'No surveys found', 'data' => $surveys], Response::HTTP_NO_CONTENT)
                : response(['message' => 'Surveys found', 'data' => $surveys], Response::HTTP_OK);
        }
        return  response(['message' => 'Some database error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getSurveysWithQuestionAndAnswers()
    {
        $surveys = Survey::where(['status' => StatusEnum::ACTIVE])->with('questions.answers')->orderBy('created_at', 'desc')->get();
        if ($surveys) {
            return count($surveys) < 1
                ? response(['message' => 'No surveys found', 'data' => $surveys], Response::HTTP_NO_CONTENT)
                : response(['message' => 'Surveys found', 'data' => $surveys], Response::HTTP_OK);
        }
        return  response(['message' => 'Some database error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function showSingle($id)
    {
        $survey = Survey::where(['id' => $id, 'status' => StatusEnum::ACTIVE])->without('creator')->first();
        return $survey
            ? response(['message' => 'Survey found', 'data' => $survey], Response::HTTP_OK)
            : response(['message' => 'Survey not found'], Response::HTTP_NOT_FOUND);
    }

    public function getSurveyWithCreator($id)
    {
        $survey = Survey::where(['id' => $id, 'status' => StatusEnum::ACTIVE])->first();
        return $survey
            ? response(['message' => 'Survey found', 'data' => $survey], Response::HTTP_OK)
            : response(['message' => 'Survey not found'], Response::HTTP_NOT_FOUND);
    }

    public function getSurveyWithQuestions($id)
    {
        $survey = Survey::where(['id' => $id, 'status' => StatusEnum::ACTIVE])->with('questions')->first();
        return $survey
            ? response(['message' =>'Survey found', 'data' => $survey], Response::HTTP_OK)
            : response(['message' => 'Survey not found'], Response::HTTP_NOT_FOUND);
    }

    public function store(Request $request)
    {
        $request->validate([
            'created_by' => 'required|integer|exists:users,id',
            'title' => 'required|string|max:100',
            'status' => ['required', new Enum(StatusEnum::class, StatusEnum::class)],

        ], [
            'title.required' => 'Survey title is required',
            'created_by.exists' => 'Sorry user id for created by feild does not exist'
        ]);

        $newSurvey = new Survey([
            'title' => $request->title,
            'created_by' => $request->created_by,
            'status' => $request->status
        ]);
        return $newSurvey->save() ?
            response(['message' => 'Survey created successfully', 'data' => $newSurvey], Response::HTTP_CREATED)
            : response(['message' => 'Survey could not be created'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'created_by' => 'integer|exists:users,id',
            'title' => 'string|max:100',
            'status' => [new Enum(StatusEnum::class, StatusEnum::class)]
        ], [
            'created_by.exists' => 'User with id for created_by field doesn\'t exist'
        ]);

        $survey = Survey::without('creator')->find($id);
        if ($survey) {
            $updates = [];
            if($request->filled('created_by')) {$updates['created_by'] = $request->created_by;}
            if($request->filled('title')) {$updates['title'] = $request->title;}
            if($request->filled('status')) {$updates['status'] = $request->status;}

            return $survey->update($updates)
                ? response(['message' => 'Survey updated successfully', 'data' => Survey::without('creator')->find($id)], Response::HTTP_OK)
                : response(['message' => 'Survey could not be found'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response(['message' => 'Survey could not be found'], Response::HTTP_NOT_FOUND);
    }

    public function delete($id)
    {
        $survey = Survey::find($id);
        if($survey){
            return $survey->delete()
                ? response(['message' => 'survey deleted successfully'], Response::HTTP_OK)
                : response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response(['message' => 'Survey does\'nt exist'], Response::HTTP_NOT_FOUND);
    }
}
