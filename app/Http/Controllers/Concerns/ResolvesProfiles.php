<?php

declare(strict_types=1);

namespace App\Http\Controllers\Concerns;

use App\Models\Profile;
use Illuminate\Http\Request;

trait ResolvesProfiles
{
    protected function activeProfile(Request $request): ?Profile
    {
        $profileId = $request->session()->get('active_profile_id');

        return $profileId ? Profile::query()->find($profileId) : null;
    }

    protected function canManageProfile(Request $request, Profile $profile): bool
    {
        $activeProfile = $this->activeProfile($request);

        if (! $activeProfile) {
            return false;
        }

        if ($activeProfile->is($profile)) {
            return true;
        }

        return $activeProfile->children()->whereKey($profile->getKey())->exists();
    }

    protected function permissionsFor(Request $request, Profile $profile): array
    {
        $activeProfile = $this->activeProfile($request);
        $owner = $activeProfile?->is($profile) ?? false;
        $parent = $activeProfile
            ? $activeProfile->children()->whereKey($profile->getKey())->exists()
            : false;

        return [
            'owner' => $owner,
            'parent' => $parent,
            'guest' => filled($request->session()->get('guest_name')),
            'canManage' => $owner || $parent,
        ];
    }
}
