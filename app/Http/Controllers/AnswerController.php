<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Rules\ValidateAnswer;
use Symfony\Component\HttpFoundation\Response;

class AnswerController extends Controller
{
    public function show()
    {
        $answers = Answer::orderBy('created_at', 'desc')->get();
        if ($answers) {
            return count($answers) < 1
                ? response(['message' => 'No answers found', 'data' => $answers], Response::HTTP_NO_CONTENT)
                : response(['message' => 'Answers found', 'data' => $answers], Response::HTTP_OK);
        }
        return  response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getAnswersWithQuestions()
    {
        $answers = Answer::with('question')->orderBy('created_at', 'desc')->get();
        if ($answers) {
            return count($answers) < 1
                ? response(['message' => 'No answers found', 'data' => $answers], Response::HTTP_NO_CONTENT)
                : response(['message' => 'Answers found', 'data' => $answers], Response::HTTP_OK);
        }
        return  response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getUserAnswers($user_id)
    {
        $answers = Answer::where(['answered_by' => $user_id])->get();
        if ($answers) {
            return count($answers) < 1
                ? response(['message' => 'No answers found', 'data' => $answers], Response::HTTP_NO_CONTENT)
                : response(['message' => 'Answers found', 'data' => $answers], Response::HTTP_OK);
        }
        return  response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getSurveyAnswers($survey_id)
    {
        $answers = Answer::whereHas('question', function ($query) use ($survey_id) {
            $query->where('questions.survey_id', $survey_id);
        })->get();
        if ($answers) {
            return count($answers) < 1
                ? response(['message' => 'No answers found', 'data' => $answers], Response::HTTP_NO_CONTENT)
                : response(['message' => 'Answers found', 'data' => $answers], Response::HTTP_OK);
        }
        return  response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function showSingle($id)
    {
        $answer = Answer::find($id);
        return $answer
            ? response(['message' => 'Answer found', 'data' => $answer], Response::HTTP_OK)
            : response(['message' => 'Answer not found'], Response::HTTP_NOT_FOUND);
    }

    public function getAnswerWithQuestion($id)
    {
        $answer = Answer::with('question')->find($id);
        return $answer
            ? response(['message' => 'Answer found', 'data' => $answer], Response::HTTP_OK)
            : response(['message' => 'Answer not found'], Response::HTTP_NOT_FOUND);
    }

    public function store(Request $request)
    {
        $request->validate([
            'answered_by' => 'required|integer|exists:users,id',
            'question_id' => 'required|integer|exists:questions,id',
            'answer' => ['required', new ValidateAnswer],
        ], [
            'answered_by.exists' => 'Sorry user with id for answered_by field does not exist',
            'question_id.exists' => 'Sorry question with id for question_id field does not exist',
            'question_id.required' => 'Question id is required',
            'answer.required' => 'Answer is required',
        ]);

        $newAnswer = new Answer([
            'answered_by' => $request->answered_by,
            'question_id' => $request->question_id,
            'answer' => is_array($request->answer) ? json_encode($request->answer) : $request->answer
        ]);

        return $newAnswer->save()
            ? response(['message' => 'Question created successfully', 'data' => $newAnswer], Response::HTTP_CREATED)
            : response(['message' => 'Question could not be created'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'answered_by' => 'integer|exists:users,id',
            'question_id' => 'integer|exists:questions,id',
            'answer' => [new ValidateAnswer($id)]
        ], [
            'answered_by.exists' => 'Sorry user with id for answered_by field does not exist',
            'question_id.exists' => 'Sorry question with id for question_id field does not exist'
        ]);

        $answer = Answer::find($id);
        if ($answer) {
            $updates = [];
            if ($request->filled('answered_by')) {
                $updates['answered_by'] = $request->answered_by;
            }
            if ($request->filled('question_id')) {
                $updates['question_id'] = $request->question_id;
            }
            if ($request->filled('answer')) {
                $updates['answer'] = $request->answer;
            }

            return $answer->update($updates)
                ? response(['message' => 'Answer updated successfully', 'data' => Answer::find($id)], Response::HTTP_OK)
                : response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response(['message' => 'Answer could not be found'], Response::HTTP_NOT_FOUND);

        return response('answer updated successfully', 200);
    }

    public function delete($id)
    {
        $answer = Answer::find($id);
        if ($answer) {
            return $answer->delete()
                ? response(['message' => 'Answer deleted successfully'], Response::HTTP_OK)
                : response(['message' => 'Error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response(['message' => 'Answer doesn\'t exist'], Response::HTTP_NOT_FOUND);
    }
}
