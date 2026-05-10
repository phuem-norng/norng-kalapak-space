<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamMemberResource;
use App\Models\ActivityLog;
use App\Models\TeamMember;
use App\Services\SupabaseStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamMemberController extends Controller
{
    public function index(): JsonResponse
    {
        $members = TeamMember::orderBy('display_order')->get();

        return response()->json([
            'success' => true,
            'data' => TeamMemberResource::collection($members),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'github_url' => ['nullable', 'url'],
            'linkedin_url' => ['nullable', 'url'],
            'telegram_url' => ['nullable', 'url'],
            'email' => ['nullable', 'email'],
            'is_founder' => ['boolean'],
            'display_order' => ['integer'],
            'is_visible' => ['boolean'],
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->uploadAvatarOrFail($request);
        }

        $member = TeamMember::create($data);

        ActivityLog::log('created', "Added team member: {$member->name}", $member);

        return response()->json([
            'success' => true,
            'data' => new TeamMemberResource($member),
            'message' => 'Team member added successfully.',
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $member = TeamMember::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'github_url' => ['nullable', 'url'],
            'linkedin_url' => ['nullable', 'url'],
            'telegram_url' => ['nullable', 'url'],
            'email' => ['nullable', 'email'],
            'is_founder' => ['boolean'],
            'display_order' => ['integer'],
            'is_visible' => ['boolean'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($member->avatar) {
                app(SupabaseStorage::class)->delete($member->avatar);
            }
            $data['avatar'] = $this->uploadAvatarOrFail($request);
        }

        $member->update($data);

        ActivityLog::log('updated', "Updated team member: {$member->name}", $member);

        return response()->json([
            'success' => true,
            'data' => new TeamMemberResource($member->fresh()),
            'message' => 'Team member updated successfully.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $member = TeamMember::findOrFail($id);
        $name = $member->name;
        if ($member->avatar) {
            app(SupabaseStorage::class)->delete($member->avatar);
        }
        $member->delete();

        ActivityLog::log('deleted', "Removed team member: {$name}");

        return response()->json([
            'success' => true,
            'message' => 'Team member removed successfully.',
        ]);
    }

    private function uploadAvatarOrFail(Request $request): string
    {
        try {
            $path = app(SupabaseStorage::class)->upload($request->file('avatar'), 'team');
            if ($path === false) {
                throw new \RuntimeException('Supabase upload returned false.');
            }
            return $path;
        } catch (\Throwable $e) {
            report($e);
            throw ValidationException::withMessages([
                'avatar' => ['Could not upload image. Check Supabase env vars (SUPABASE_URL, bucket, secret key) on the server.'],
            ]);
        }
    }
}
