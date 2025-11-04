<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // Client dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $clientProfile = $user->clientProfile;

        if (!$clientProfile->coach_id) {
            return view('client.dashboard', ['coach' => null, 'stats' => []]);
        }

        $coach = User::find($clientProfile->coach_id);

        $stats = [
            'current_weight' => $clientProfile->getLatestWeight(),
            'fitness_goal' => $clientProfile->fitness_goal,
            'start_date' => $clientProfile->start_date,
            'bmi' => $clientProfile->calculateBMI(),
        ];

        $recentPhotos = $clientProfile->progressPhotos()
            ->latest()
            ->limit(3)
            ->get();

        return view('client.dashboard', compact('coach', 'stats', 'recentPhotos'));
    }

    // View all meal plans
    public function viewMealPlans()
    {
        $user = Auth::user();
        $mealPlans = User::find($user->id)->where('role', 'coach')
            ->first()
            ->mealPlans()
            ->where('client_id', $user->id)
            ->paginate(10);

        return view('client.meal-plans', compact('mealPlans'));
    }

    // View single meal plan
    public function viewMealPlan($mealPlanId)
    {
        $user = Auth::user();
        $mealPlan = $user->mealPlans()
            ->where('client_id', $user->id)
            ->findOrFail($mealPlanId);

        $mealsByDay = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $day) {
            $mealsByDay[$day] = $mealPlan->getMealsByDay($day);
        }

        return view('client.meal-plan-detail', compact('mealPlan', 'mealsByDay'));
    }

    // View all workout plans
    public function viewWorkoutPlans()
    {
        $user = Auth::user();
        $workoutPlans = User::find($user->id)->where('role', 'coach')
            ->first()
            ->workoutPlans()
            ->where('client_id', $user->id)
            ->paginate(10);

        return view('client.workout-plans', compact('workoutPlans'));
    }

    // View single workout plan
    public function viewWorkoutPlan($workoutPlanId)
    {
        $user = Auth::user();
        $workoutPlan = $user->workoutPlans()
            ->where('client_id', $user->id)
            ->findOrFail($workoutPlanId);

        $exercises = $workoutPlan->exercises()->get();

        return view('client.workout-plan-detail', compact('workoutPlan', 'exercises'));
    }

    // Update client profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $clientProfile = $user->clientProfile;

        $validated = $request->validate([
            'age' => 'sometimes|integer|min:1|max:150',
            'height' => 'sometimes|numeric|min:50',
            'weight' => 'sometimes|numeric|min:20',
            'fitness_goal' => 'sometimes|string|max:255',
        ]);

        $clientProfile->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
