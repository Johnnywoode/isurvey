<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function surveys(){
      return Survey::where('status', StatusEnum::ACTIVE)->with(['questions', 'creator'])->get();
    }

    public function store(Request $request){
      $user = auth()->user();
      $request->validate([
        'title' => 'required|string|max:100'
      ],[
          'title.required'=>'Survey title is required'
      ]);

      $newSurvey = [
        'title' => $request->title,
        'created_by' => $user,
        'status' => StatusEnum::ACTIVE
      ];

      return $newSurvey;
    }
}
