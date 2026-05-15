<?php

use App\Http\Controllers\Admin\QuestionnaireController as AdminQuestionnaireController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');

// Admin routes — protégées
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::apiResource('questionnaires', AdminQuestionnaireController::class);
    Route::post('questionnaires/{questionnaire}/questions/reorder', [AdminQuestionController::class, 'reorder']);
    Route::apiResource('questionnaires.questions', AdminQuestionController::class)->shallow();
    Route::get('questionnaires/{questionnaire}/responses', [ResponseController::class, 'index']);
});

// Client routes — publiques
Route::get('questionnaires/{questionnaire}', [QuestionnaireController::class, 'show']);
Route::post('questionnaires/{questionnaire}/responses', [ResponseController::class, 'store']);
