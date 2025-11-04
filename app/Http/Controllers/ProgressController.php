<?php

namespace App\Http\Controllers;

use App\Models\ProgressPhoto;
use App\Models\ProgressTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    // Show upload photo form
    public function showUploadPhotoForm()
    {
        return view('client.progress.upload-photo');
    }

    // Store progress photo
    public function storeProgressPhoto(Request $request)
    {
        $user = Auth::user();
        $client = $user->clientProfile;

        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'weight' => 'required|numeric|min:20',
            'body_notes' => 'nullable|string|max:500',
        ]);

        $photoPath = $request->file('photo')->store('progress_photos', 'public');

        ProgressPhoto::create([
            'client_id' => $user->id,
            'photo_url' => $photoPath,
            'upload_date' => now(),
            'weight' => $validated['weight'],
            'body_notes' => $validated['body_notes'],
        ]);

        return redirect()->route('client.progress-photos')
            ->with('success', 'Progress photo uploaded successfully!');
    }

    // View all progress photos
    public function viewProgressPhotos()
    {
        $user = Auth::user();
        $photos = $user->clientProfile->progressPhotos()
            ->latest()
            ->paginate(12);

        return view('client.progress.photos', compact('photos'));
    }

    // Delete progress photo
    public function deleteProgressPhoto($photoId)
    {
        $user = Auth::user();
        $photo = ProgressPhoto::findOrFail($photoId);

        if ($photo->client_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $photo->delete();

        return back()->with('success', 'Photo deleted successfully!');
    }

    // Show log progress form
    public function showLogProgressForm()
    {
        return view('client.progress.log');
    }

    // Store progress tracking
    public function storeProgressTracking(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'weight' => 'required|numeric|min:20',
            'body_fat_percentage' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        ProgressTracking::create([
            'client_id' => $user->id,
            'log_date' => now(),
            'weight' => $validated['weight'],
            'body_fat_percentage' => $validated['body_fat_percentage'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('client.progress-tracking')
            ->with('success', 'Progress logged successfully!');
    }

    // View progress tracking
    public function viewProgressTracking()
    {
        $user = Auth::user();
        $progressData = $user->clientProfile->progressTracking()
            ->latest('log_date')
            ->paginate(20);

        return view('client.progress.tracking', compact('progressData'));
    }

    // Get progress data for chart (JSON)
    public function getProgressChartData()
    {
        $user = Auth::user();
        $data = $user->clientProfile->progressTracking()
            ->select('log_date', 'weight')
            ->orderBy('log_date')
            ->get();

        return response()->json([
            'labels' => $data->pluck('log_date')->map(fn($date) => $date->format('M d')),
            'weights' => $data->pluck('weight'),
        ]);
    }

    // Coach view client progress
    public function coachViewClientProgress($clientUserId)
    {
        $coach = Auth::user();
        $client = $coach->mealPlans()
            ->where('client_id', $clientUserId)
            ->first()
            ->client;

        if (!$client) {
            abort(403, 'Unauthorized');
        }

        $progressPhotos = $client->progressPhotos()
            ->latest()
            ->paginate(12);

        $progressTracking = $client->progressTracking()
            ->latest('log_date')
            ->get();

        return view('coach.client-progress', compact('client', 'progressPhotos', 'progressTracking'));
    }
}
