<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Enums\StatusEnum;
use App\Enums\QuestionType;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
  public function show()
  {
    $questions = Question::where(['status' => StatusEnum::ACTIVE])->orderBy('created_at', 'desc')->get();
    if ($questions) {
        return count($questions) < 1
            ? response(['message' => 'No questions found', 'data' => $questions], Response::HTTP_NO_CONTENT)
            : response(['message' => 'Questions found', 'data' => $questions], Response::HTTP_OK);
    }
    return  response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
  }

  public function getQuestionWithAnswers($question_id)
  {
    $question = Question::where(['id' => $question_id, 'status' => StatusEnum::ACTIVE])->with('answers')->first();
    return $question
        ? response(['message' => 'Question found', 'data' => $question], Response::HTTP_OK)
        : response(['message' => 'Question not found'], Response::HTTP_NOT_FOUND);
  }

  public function getSurveyQuestionsWithAnswers($survey_id)
  {
    $questions = Question::where(['survey_id' => $survey_id, 'status' => StatusEnum::ACTIVE])->with('answers')->get();
    if ($questions) {
        return count($questions) < 1
            ? response(['message' => 'No questions found', 'data' => $questions], Response::HTTP_NO_CONTENT)
            : response(['message' => 'Questions found', 'data' => $questions], Response::HTTP_OK);
    }
    return  response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
  }

  public function showSingle($id)
  {
    $question = Question::where(['id' => $id, 'status' => StatusEnum::ACTIVE])->first();
    return $question
        ? response(['message' => 'Question found', 'data' => $question], Response::HTTP_OK)
        : response(['message' => 'Question not found'], Response::HTTP_NOT_FOUND);
  }

  public function getQuestionWithSurvey($question_id)
  {
    $question = Question::where(['id' => $question_id, 'status' => StatusEnum::ACTIVE])->with('survey')->first();
    return $question
        ? response(['message' => 'Question found', 'data' => $question], Response::HTTP_OK)
        : response(['message' => 'Question not found'], Response::HTTP_NOT_FOUND);
  }

  public function store(Request $request)
  {
    $request->validate([
      'survey_id' => 'required|integer|exists:surveys,id',
      'question' => 'required|string|max:500',
      'type' => ['required', new Enum(QuestionType::class, QuestionType::class)],
      'options' => [Rule::requiredIf(in_array($request->type, [QuestionType::NUMBER, QuestionType::TEXT]))],
      'status' => ['required', new Enum(StatusEnum::class, StatusEnum::class)]
    ], [
      'survey_id.required' => 'Survey id  is required',
      'question.required' => 'Question title is required',
      'type.required' => 'Question type is required',
      'status.required' => 'Question status is required',
    ]);

    $newQuestion = new Question([
      'survey_id' => $request->survey_id,
      'question' => $request->question,
      'type' => $request->type,
      'status' => $request->status
    ]);
    if($request->filled('options')) {$newQuestion['options'] = $request->options;}

    return $newQuestion->save()
        ? response(['message' => 'Question created successfully', 'data' => $newQuestion], Response::HTTP_CREATED)
        : response(['message' => 'Question could not be created'], Response::HTTP_INTERNAL_SERVER_ERROR);
  }

  public function update($id, Request $request)
  {
    $request->validate([
        'survey_id' => 'integer|exists:surveys,id',
        'question' => 'string|max:500',
        'type' => [new Enum(QuestionType::class, QuestionType::class)],
        'options' => [Rule::requiredIf(in_array($request->type, [QuestionType::NUMBER, QuestionType::TEXT]))],
        'status' => [new Enum(StatusEnum::class, StatusEnum::class)]
    ]);

    $question = Question::find($id);
    if ($question) {
        $updates = [];
        if ($request->filled('survey_id')) {
            $updates['survey_id'] = $request->survey_id;
        }
        if ($request->filled('question')) {
            $updates['question'] = $request->question;
        }
        if ($request->filled('type')) {
            $updates['type'] = $request->type;
        }
        if ($request->filled('options')) {
            $updates['options'] = $request->options;
        }
        if ($request->filled('status')) {
            $updates['status'] = $request->status;
        }

        return $question->update($updates)
            ? response(['message' => 'Question updated successfully', 'data' => Question::find($id)], Response::HTTP_OK)
            : response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    return response(['message' => 'Question could not be found'], Response::HTTP_NOT_FOUND);
  }

  public function delete($id)
  {
    $question = Question::find($id);
    if ($question) {
        return $question->delete()
            ? response(['message' => 'Question deleted successfully'], Response::HTTP_OK)
            : response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    return response(['message' => 'Question does\'nt exist'], Response::HTTP_NOT_FOUND);
  }
}
