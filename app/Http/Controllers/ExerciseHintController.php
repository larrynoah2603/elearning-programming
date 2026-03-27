<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseHint;
use App\Models\ExerciseHintView;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExerciseHintController extends Controller
{
    public function show(Request $request, Exercise $exercise, int $level): JsonResponse
    {
        abort_unless($level >= 1 && $level <= 3, 404);

        $user = $request->user();

        if (!$exercise->isAccessibleBy($user)) {
            return response()->json(['message' => 'Subscription required'], 403);
        }

        if ($level > 1) {
            $hasPrevious = ExerciseHintView::query()
                ->where('user_id', $user->id)
                ->where('exercise_id', $exercise->id)
                ->where('hint_level', $level - 1)
                ->exists();

            if (!$hasPrevious) {
                return response()->json([
                    'success' => false,
                    'message' => 'Débloquez d\'abord l\'indice précédent.',
                ], 422);
            }
        }

        $hint = ExerciseHint::query()
            ->where('exercise_id', $exercise->id)
            ->where('level', $level)
            ->first();

        if (!$hint) {
            $fallback = trim((string) $exercise->hints);
            if ($fallback === '' || $level > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun indice disponible pour ce niveau.',
                ], 404);
            }

            $hintText = $fallback;
        } else {
            $hintText = $hint->content;
        }

        ExerciseHintView::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'exercise_id' => $exercise->id,
                'hint_level' => $level,
            ],
            [
                'viewed_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'level' => $level,
            'content' => $hintText,
        ]);
    }
}
