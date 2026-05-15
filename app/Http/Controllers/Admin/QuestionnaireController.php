<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Questionnaire::withCount('questions', 'responses')->latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        return response()->json(Questionnaire::create($data), 201);
    }

    public function show(Questionnaire $questionnaire): JsonResponse
    {
        return response()->json($questionnaire->load('questions'));
    }

    public function update(Request $request, Questionnaire $questionnaire): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $questionnaire->update($data);

        return response()->json($questionnaire);
    }

    public function destroy(Questionnaire $questionnaire): JsonResponse
    {
        $questionnaire->delete();

        return response()->json(null, 204);
    }
}
