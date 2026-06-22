<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Profile;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $activeProfileId = $request->session()->get('active_profile_id');

        return [
            ...parent::share($request),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'locale' => app()->getLocale(),
            'supportedLocales' => ['fr', 'en'],
            'session' => [
                'activeProfile' => fn () => $activeProfileId
                    ? Profile::query()->find($activeProfileId)?->only(['id', 'name', 'avatar'])
                    : null,
                'guestName' => fn () => $request->session()->get('guest_name'),
            ],
        ];
    }
}
