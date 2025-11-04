<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\MealPlanItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealPlanController extends Controller
{
    // Show create form
    public function create()
    {
        $coach = Auth::user();
        $clients = $coach->coachProfile->clients()->with('user')->get();

        return view('coach.meal-plans.create', compact('clients'));
    }

    // Store meal plan
    public function store(Request $request)
    {
        $coach = Auth::user();

        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meals' => 'required|array',
            'meals.*.day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'meals.*.meal_type' => 'required|in:breakfast,lunch,dinner',
            'meals.*.description' => 'required|string',
            'meals.*.calories' => 'required|numeric|min:0',
        ]);

        // Verify client belongs to coach
        $client = User::findOrFail($validated['client_id']);
        if ($client->clientProfile->coach_id !== $coach->id) {
            abort(403, 'Unauthorized');
        }

        // Create meal plan
        $mealPlan = MealPlan::create([
            'coach_id' => $coach->id,
            'client_id' => $validated['client_id'],
            'plan_name' => $validated['plan_name'],
            'description' => $validated['description'],
        ]);

        // Create meal items
        foreach ($validated['meals'] as $meal) {
            MealPlanItem::create([
                'meal_plan_id' => $mealPlan->id,
                'day' => $meal['day'],
                'meal_type' => $meal['meal_type'],
                'description' => $meal['description'],
                'calories' => $meal['calories'],
            ]);
        }

        return redirect()->route('coach.dashboard')
            ->with('success', 'Meal plan created successfully!');
    }

    // Show edit form
    public function edit($mealPlanId)
    {
        $coach = Auth::user();
        $mealPlan = $coach->mealPlans()->findOrFail($mealPlanId);

        $clients = $coach->coachProfile->clients()->with('user')->get();
        $mealItems = $mealPlan->mealItems()->get();

        return view('coach.meal-plans.edit', compact('mealPlan', 'clients', 'mealItems'));
    }

    // Update meal plan
    public function update(Request $request, $mealPlanId)
    {
        $coach = Auth::user();
        $mealPlan = $coach->mealPlans()->findOrFail($mealPlanId);

        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meals' => 'required|array',
            'meals.*.day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'meals.*.meal_type' => 'required|in:breakfast,lunch,dinner',
            'meals.*.description' => 'required|string',
            'meals.*.calories' => 'required|numeric|min:0',
        ]);

        // Update meal plan
        $mealPlan->update([
            'plan_name' => $validated['plan_name'],
            'description' => $validated['description'],
        ]);

        // Delete existing meals
        $mealPlan->mealItems()->delete();

        // Create new meals
        foreach ($validated['meals'] as $meal) {
            MealPlanItem::create([
                'meal_plan_id' => $mealPlan->id,
                'day' => $meal['day'],
                'meal_type' => $meal['meal_type'],
                'description' => $meal['description'],
                'calories' => $meal['calories'],
            ]);
        }

        return redirect()->route('coach.dashboard')
            ->with('success', 'Meal plan updated successfully!');
    }

    // Delete meal plan
    public function destroy($mealPlanId)
    {
        $coach = Auth::user();
        $mealPlan = $coach->mealPlans()->findOrFail($mealPlanId);

        $mealPlan->mealItems()->delete();
        $mealPlan->delete();

        return redirect()->route('coach.dashboard')
            ->with('success', 'Meal plan deleted successfully!');
    }
}
