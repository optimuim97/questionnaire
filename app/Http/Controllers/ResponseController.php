<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Questionnaire;
use App\Models\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function store(Request $request, Questionnaire $questionnaire): JsonResponse
    {
        abort_if(! $questionnaire->is_active, 404);

        $data = $request->validate([
            'respondent_name'  => 'nullable|string|max:255',
            'respondent_email' => 'nullable|email|max:255',
            'answers'          => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.value'       => 'required',
        ]);

        $response = Response::create([
            'questionnaire_id' => $questionnaire->id,
            'respondent_name'  => $data['respondent_name'] ?? null,
            'respondent_email' => $data['respondent_email'] ?? null,
        ]);

        foreach ($data['answers'] as $answerData) {
            Answer::create([
                'response_id' => $response->id,
                'question_id' => $answerData['question_id'],
                'value'       => is_array($answerData['value']) ? $answerData['value'] : [$answerData['value']],
            ]);
        }

        return response()->json(['message' => 'Réponses enregistrées', 'response_id' => $response->id], 201);
    }

    public function index(Questionnaire $questionnaire): JsonResponse
    {
        $responses = $questionnaire->responses()
            ->with(['answers.question'])
            ->latest()
            ->get();

        return response()->json($responses);
    }
}
