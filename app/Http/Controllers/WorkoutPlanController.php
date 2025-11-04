<?php

namespace App\Http\Controllers;

use App\Models\WorkoutPlan;
use App\Models\WorkoutExercise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutPlanController extends Controller
{
    // Show create form
    public function create()
    {
        $coach = Auth::user();
        $clients = $coach->coachProfile->clients()->with('user')->get();

        return view('coach.workout-plans.create', compact('clients'));
    }

    // Store workout plan
    public function store(Request $request)
    {
        $coach = Auth::user();

        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'exercises' => 'required|array',
            'exercises.*.exercise_name' => 'required|string',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.duration' => 'required|integer|min:1',
            'exercises.*.rest_period' => 'required|integer|min:0',
            'exercises.*.video_url' => 'nullable|url',
        ]);

        // Verify client belongs to coach
        $client = User::findOrFail($validated['client_id']);
        if ($client->clientProfile->coach_id !== $coach->id) {
            abort(403, 'Unauthorized');
        }

        // Create workout plan
        $workoutPlan = WorkoutPlan::create([
            'coach_id' => $coach->id,
            'client_id' => $validated['client_id'],
            'plan_name' => $validated['plan_name'],
            'description' => $validated['description'],
            'difficulty_level' => $validated['difficulty_level'],
        ]);

        // Create exercises
        foreach ($validated['exercises'] as $exercise) {
            WorkoutExercise::create([
                'workout_plan_id' => $workoutPlan->id,
                'exercise_name' => $exercise['exercise_name'],
                'sets' => $exercise['sets'],
                'reps' => $exercise['reps'],
                'duration' => $exercise['duration'],
                'rest_period' => $exercise['rest_period'],
                'video_url' => $exercise['video_url'] ?? null,
            ]);
        }

        return redirect()->route('coach.dashboard')
            ->with('success', 'Workout plan created successfully!');
    }

    // Show edit form
    public function edit($workoutPlanId)
    {
        $coach = Auth::user();
        $workoutPlan = $coach->workoutPlans()->findOrFail($workoutPlanId);

        $clients = $coach->coachProfile->clients()->with('user')->get();
        $exercises = $workoutPlan->exercises()->get();

        return view('coach.workout-plans.edit', compact('workoutPlan', 'clients', 'exercises'));
    }

    // Update workout plan
    public function update(Request $request, $workoutPlanId)
    {
        $coach = Auth::user();
        $workoutPlan = $coach->workoutPlans()->findOrFail($workoutPlanId);

        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'exercises' => 'required|array',
            'exercises.*.exercise_name' => 'required|string',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.duration' => 'required|integer|min:1',
            'exercises.*.rest_period' => 'required|integer|min:0',
            'exercises.*.video_url' => 'nullable|url',
        ]);

        // Update workout plan
        $workoutPlan->update([
            'plan_name' => $validated['plan_name'],
            'description' => $validated['description'],
            'difficulty_level' => $validated['difficulty_level'],
        ]);

        // Delete existing exercises
        $workoutPlan->exercises()->delete();

        // Create new exercises
        foreach ($validated['exercises'] as $exercise) {
            WorkoutExercise::create([
                'workout_plan_id' => $workoutPlan->id,
                'exercise_name' => $exercise['exercise_name'],
                'sets' => $exercise['sets'],
                'reps' => $exercise['reps'],
                'duration' => $exercise['duration'],
                'rest_period' => $exercise['rest_period'],
                'video_url' => $exercise['video_url'] ?? null,
            ]);
        }

        return redirect()->route('coach.dashboard')
            ->with('success', 'Workout plan updated successfully!');
    }

    // Delete workout plan
    public function destroy($workoutPlanId)
    {
        $coach = Auth::user();
        $workoutPlan = $coach->workoutPlans()->findOrFail($workoutPlanId);

        $workoutPlan->exercises()->delete();
        $workoutPlan->delete();

        return redirect()->route('coach.dashboard')
            ->with('success', 'Workout plan deleted successfully!');
    }
}
