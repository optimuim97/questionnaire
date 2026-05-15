<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use Illuminate\Http\JsonResponse;

class QuestionnaireController extends Controller
{
    public function show(Questionnaire $questionnaire): JsonResponse
    {
        abort_if(! $questionnaire->is_active, 404);

        return response()->json($questionnaire->load('questions'));
    }
}
