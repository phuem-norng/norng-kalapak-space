<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private function frontendUrl(): string
    {
        return rtrim(env('FRONTEND_URL', 'http://localhost:3000'), '/');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            $frontendUrl = $this->frontendUrl();
            return redirect($frontendUrl . '/auth/login?error=google_auth_failed');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id if user exists by email but hasn't linked Google yet
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
            // Update avatar from Google if user doesn't have one
            if (!$user->avatar && $googleUser->getAvatar()) {
                $user->update(['avatar' => $googleUser->getAvatar()]);
            }
        } else {
            // Create new user
            $memberRole = Role::where('name', 'member')->first();
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null,
                'role_id' => $memberRole->id,
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        $frontendUrl = $this->frontendUrl();

        return redirect($frontendUrl . '/auth/google/callback?token=' . $token);
    }

    public function redirectToGithub(): RedirectResponse
    {
        return Socialite::driver('github')->stateless()->redirect();
    }

    public function handleGithubCallback(): RedirectResponse
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();
        } catch (\Exception $e) {
            $frontendUrl = $this->frontendUrl();
            return redirect($frontendUrl . '/auth/login?error=github_auth_failed');
        }

        $user = User::where('github_id', $githubUser->getId())
            ->orWhere('email', $githubUser->getEmail())
            ->first();

        if ($user) {
            if (!$user->github_id) {
                $user->update(['github_id' => $githubUser->getId()]);
            }
            if (!$user->avatar && $githubUser->getAvatar()) {
                $user->update(['avatar' => $githubUser->getAvatar()]);
            }
        } else {
            $memberRole = Role::where('name', 'member')->first();
            $user = User::create([
                'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                'email' => $githubUser->getEmail(),
                'github_id' => $githubUser->getId(),
                'avatar' => $githubUser->getAvatar(),
                'password' => null,
                'role_id' => $memberRole->id,
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        $frontendUrl = $this->frontendUrl();

        return redirect($frontendUrl . '/auth/github/callback?token=' . $token);
    }
}
