<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class UserProfileController extends Controller
{
    /**
     * Повертає профіль користувача
     */
    public function index(Request $request)
    {
        $profile = UserProfile::first();
        return response()->json($profile);
    }

    /**
     * Зберігає профіль користувача.
     */
    public function store(Request $request)
    {
        if (!Schema::hasColumn('user_profiles', 'email')) {
            return response()->json(['error' => 'Помилка бази даних: Колонка "email" відсутня.'], 500);
        }

        $validated = $request->validate([
            'user_name'  => 'required|regex:/^[a-zA-Z0-9\s]+$/u|max:255',
            'email'      => 'required|email|max:255',
            'home_page'  => 'nullable|url|max:255',
            'avatar_url' => 'nullable|string|max:500',
        ]);

        $profile = UserProfile::updateOrCreate(
            ['user_name' => $validated['user_name']], // Оновлюємо профіль за user_name, а не email
            $validated
        );

        return response()->json($profile, 201);
    }

    /**
     * (для тестування).
     */
    public function destroy(Request $request)
    {
        $profile = UserProfile::first();
        if ($profile) {
            $profile->delete();
        }
        return response()->json(['message' => 'Профіль скинуто']);
    }
}
