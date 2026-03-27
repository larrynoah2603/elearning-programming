<?php

namespace App\Http\Controllers;

use App\Models\LearningPlanItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LearningPlanController extends Controller
{
    public function completeItem(Request $request, LearningPlanItem $item): RedirectResponse
    {
        if ($item->plan->user_id !== $request->user()->id) {
            abort(403);
        }

        $item->update([
            'is_done' => true,
            'done_at' => now(),
        ]);

        return back()->with('success', 'Étape marquée comme terminée.');
    }
}
