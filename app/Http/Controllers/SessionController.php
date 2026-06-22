<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SessionController extends Controller
{
    public function createGuest(): Response
    {
        return Inertia::render('Guest');
    }

    public function profile(Request $request, Profile $profile): RedirectResponse
    {
        abort_if($profile->is_child, 403);

        $request->session()->forget('guest_name');
        $request->session()->put('active_profile_id', $profile->id);

        return to_route('profiles.show', $profile)->with('success', __('messages.session.profile_selected', [
            'name' => $profile->name,
        ]));
    }

    public function guest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:120'],
        ]);

        $request->session()->forget('active_profile_id');
        $request->session()->put('guest_name', $validated['guest_name']);

        return to_route('profiles.index')->with('success', __('messages.session.guest_enabled'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget(['active_profile_id', 'guest_name']);

        return to_route('home')->with('success', __('messages.session.closed'));
    }
}
