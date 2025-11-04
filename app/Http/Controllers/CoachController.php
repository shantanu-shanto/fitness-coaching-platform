<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CoachProfile;
use App\Models\ClientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoachController extends Controller
{
    // Coach dashboard
    public function dashboard()
    {
        $coach = Auth::user();
        $coachProfile = $coach->coachProfile;

        if (!$coachProfile) {
            return redirect()->route('home')->with('error', 'Coach profile not found');
        }

        $stats = [
            'total_clients' => $coachProfile->getTotalClientsCount(),
            'total_meal_plans' => $coachProfile->getTotalMealPlansCount(),
            'total_workout_plans' => $coachProfile->getTotalWorkoutPlansCount(),
        ];

        $clients = $coachProfile->clients()
            ->with(['user', 'progressTracking'])
            ->get();

        return view('coach.dashboard', compact('stats', 'clients'));
    }

    // List all clients
    public function listClients()
    {
        $coach = Auth::user();
        $coachProfile = $coach->coachProfile;

        if (!$coachProfile) {
            return redirect()->route('home')->with('error', 'Coach profile not found');
        }

        $clients = $coachProfile->clients()
            ->with('user')
            ->paginate(10);

        return view('coach.clients', compact('clients'));
    }

    // View single client profile
    public function viewClient($clientUserId)
    {
        $coach = Auth::user();
        $client = User::findOrFail($clientUserId)->clientProfile;

        if (!$client) {
            abort(404, 'Client not found');
        }

        // Verify client belongs to this coach
        if ($client->coach_id !== $coach->id) {
            abort(403, 'Unauthorized');
        }

        $stats = [
            'age' => $client->age,
            'height' => $client->height,
            'weight' => $client->weight,
            'bmi' => $client->calculateBMI(),
            'current_weight' => $client->getLatestWeight(),
        ];

        $progressPhotos = $client->progressPhotos()
            ->latest()
            ->get();

        $progressTracking = $client->progressTracking()
            ->latest('log_date')
            ->get();

        return view('coach.client-profile', compact('client', 'stats', 'progressPhotos', 'progressTracking'));
    }

    // Update coach profile
    public function updateProfile(Request $request)
    {
        $coach = Auth::user();
        $coachProfile = $coach->coachProfile;

        if (!$coachProfile) {
            return redirect()->route('home')->with('error', 'Coach profile not found');
        }

        $validated = $request->validate([
            'specialization' => 'sometimes|string|max:255',
            'certifications' => 'nullable|string',
            'experience_years' => 'sometimes|integer|min:0',
            'hourly_rate' => 'sometimes|numeric|min:0',
        ]);

        $coachProfile->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    // Assign client to coach
    public function assignClient(Request $request)
    {
        $validated = $request->validate([
            'client_user_id' => 'required|exists:users,id',
        ]);

        $coach = Auth::user();
        $client = User::findOrFail($validated['client_user_id'])->clientProfile;

        if (!$client) {
            return back()->with('error', 'Client not found');
        }

        $client->update(['coach_id' => $coach->id]);

        return back()->with('success', 'Client assigned successfully!');
    }
}
