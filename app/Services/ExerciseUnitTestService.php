<?php

namespace App\Services;

use App\Models\Exercise;

class ExerciseUnitTestService
{
    public function evaluate(Exercise $exercise, string $submittedCode): array
    {
        $tests = collect($exercise->unit_tests ?? [])
            ->filter(fn ($test) => is_array($test) && isset($test['type'], $test['value']))
            ->values();

        $results = $tests->map(function (array $test, int $index) use ($submittedCode) {
            $type = $test['type'];
            $value = (string) $test['value'];
            $name = $test['name'] ?? ('Test #' . ($index + 1));
            $passed = false;

            if ($type === 'contains') {
                $passed = str_contains($submittedCode, $value);
            }

            if ($type === 'not_contains') {
                $passed = !str_contains($submittedCode, $value);
            }

            if ($type === 'regex') {
                $passed = @preg_match($value, $submittedCode) === 1;
            }

            return [
                'name' => $name,
                'type' => $type,
                'expected' => $value,
                'passed' => $passed,
            ];
        });

        $total = $results->count();
        $passed = $results->where('passed', true)->count();
        $score = $total > 0 ? (int) round(($passed / $total) * 100) : null;

        return [
            'total' => $total,
            'passed' => $passed,
            'score' => $score,
            'results' => $results->all(),
        ];
    }
}
