<?php

use Illuminate\Support\Facades\Route;
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


Route::get('/surveyform/{user_id}', [App\Http\Controllers\ResponseController::class, 'index']);
Route::get('/survey-form/{user_id}', [App\Http\Controllers\ResponseController::class, 'validateUserSurveyForm']);
// Route::get('/assignment', [App\Http\Controllers\AssignmentController::class, 'index']);

Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/survey', [App\Http\Controllers\FormController::class, 'index'])->name('survey-form');
Route::get('/survey-form', [App\Http\Controllers\FormController::class, 'show'])->name('survey-form-list');
Route::get('/assignment/{assignmentid}', [App\Http\Controllers\AssignmentController::class, 'show'])->name('respondent-list');
Route::get('/question/{formid}', [App\Http\Controllers\FormSectionController::class, 'fetchFormquestion'])->name('survey-question');
Route::get('/option/{questionid}', [App\Http\Controllers\OptionController::class, 'fetchQuestionOption'])->name('survey-option');
Route::get('/survey-form-layout', [App\Http\Controllers\FormController::class, 'create'])->name('survey-form-layout');
Route::get('/survey-form-layout/{id}', [App\Http\Controllers\FormController::class, 'fetchForm']);
Route::get('/option-type', [App\Http\Controllers\OptionTypeController::class, 'index']);
Route::get('/form/user/{form_id}',[App\Http\Controllers\FormController::class, 'fetchForm']);
Route::get('/form/view/{form_id}',[App\Http\Controllers\FormController::class, 'fetchViewForm']);
Route::get('/form/view',[App\Http\Controllers\FormController::class, 'viewForm'])->name('view-survey-form');

Route::post('/form-store', [App\Http\Controllers\FormController::class, 'store']);
Route::post('/survey-form-store', [App\Http\Controllers\FormSectionController::class, 'store']);
Route::patch('/survey-form-update', [App\Http\Controllers\FormSectionController::class, 'update']);
Route::patch('/form-update', [App\Http\Controllers\FormController::class, 'update']);
Route::post('/form/user/response',[App\Http\Controllers\ResponseController::class, 'store']);

Route::patch('/publish',[App\Http\Controllers\FormController::class, 'publishForm']);

Route::get('/export/{form_id}',[App\Http\Controllers\FormController::class, 'exportToExcel']);
