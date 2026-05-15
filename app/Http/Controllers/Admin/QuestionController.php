<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Questionnaire $questionnaire): JsonResponse
    {
        return response()->json($questionnaire->questions);
    }

    public function store(Request $request, Questionnaire $questionnaire): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'explanation' => 'nullable|string',
            'input_type'  => 'required|in:text,number,email,phone,date,textarea,select,radio,checkbox',
            'options'     => 'nullable|array',
            'options.*'   => 'string',
            'required'    => 'boolean',
            'order'       => 'integer|min:0',
        ]);

        $data['questionnaire_id'] = $questionnaire->id;
        $data['order'] = $data['order'] ?? $questionnaire->questions()->count();

        return response()->json(Question::create($data), 201);
    }

    public function show(Questionnaire $questionnaire, Question $question): JsonResponse
    {
        return response()->json($question);
    }

    public function update(Request $request, Questionnaire $questionnaire, Question $question): JsonResponse
    {
        $data = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'explanation' => 'nullable|string',
            'input_type'  => 'sometimes|required|in:text,number,email,phone,date,textarea,select,radio,checkbox',
            'options'     => 'nullable|array',
            'options.*'   => 'string',
            'required'    => 'boolean',
            'order'       => 'integer|min:0',
        ]);

        $question->update($data);

        return response()->json($question);
    }

    public function destroy(Questionnaire $questionnaire, Question $question): JsonResponse
    {
        $question->delete();

        return response()->json(null, 204);
    }

    public function reorder(Request $request, Questionnaire $questionnaire): JsonResponse
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);

        foreach ($request->order as $position => $id) {
            $questionnaire->questions()->where('id', $id)->update(['order' => $position]);
        }

        return response()->json(['message' => 'Reordered']);
    }
}
