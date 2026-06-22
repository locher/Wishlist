<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Home', [
            'profiles' => Profile::query()
                ->where('is_child', false)
                ->orderBy('name')
                ->get()
                ->sortBy(fn (Profile $profile) => Str::lower(Str::ascii($profile->name)))
                ->values()
                ->map(fn (Profile $profile) => [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'avatar' => $profile->avatar,
                    'avatarUrl' => $profile->avatar_url,
                    'childrenCount' => $profile->children()->count(),
                ]),
        ]);
    }
}
