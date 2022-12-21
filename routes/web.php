<?php

use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Survey;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/users', function () {
  return User::with(['surveys'])->get();
})->name('users');

Route::get('/reset-password/{token}', function($token){
  return $token;
})->middleware(['guest:'.config('fortify.guard')])
  ->name('password.reset');



Route::prefix('test')->group(function(){
  Route::get('/login', function () { return view('test.login'); })->name('login');

  Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/users', function(){
      return view('test.test-users', ['data' => User::with(['surveys'])->get()]);
    });

    Route::get('/surveys', function(){
      return view('test.test-surveys', ['data' => Survey::where('status', StatusEnum::ACTIVE)->get()]);
    });

    Route::get('/surveys/{id}', function($id){
      return view('test.test-single-survey', ['data' => Survey::where(['id' => $id, 'status' => StatusEnum::ACTIVE])->with('questions')->first()]);
    });
  });

});
