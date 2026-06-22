<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesProfiles;
use App\Http\Requests\ProfileRequest;
use App\Models\Gift;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    use ResolvesProfiles;

    public function index(Request $request): Response|RedirectResponse
    {
        if (! $request->session()->has('active_profile_id') && ! $request->session()->has('guest_name')) {
            return to_route('home')->with('error', __('messages.access.required'));
        }

        return Inertia::render('Profiles/Index', [
            'profiles' => Profile::query()
                ->withCount('children')
                ->orderBy('name')
                ->get()
                ->sortBy(fn (Profile $profile) => $this->profileSortKey($profile))
                ->values()
                ->map(fn (Profile $profile) => $this->profilePayload($profile)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Profiles/Form', [
            'profile' => null,
            'parents' => $this->parentOptions(),
        ]);
    }

    public function store(ProfileRequest $request): RedirectResponse
    {
        $data = $request->normalized();
        $parentIds = $data['parent_ids'];
        unset($data['parent_ids']);

        $profile = Profile::query()->create($data);
        $profile->parents()->sync($data['is_child'] ? $parentIds : []);

        if (! $profile->is_child) {
            $request->session()->forget('guest_name');
            $request->session()->put('active_profile_id', $profile->id);
        }

        return to_route('profiles.show', $profile)->with('success', __('messages.profile.created'));
    }

    public function show(Request $request, Profile $profile): Response|RedirectResponse
    {
        if (! $request->session()->has('active_profile_id') && ! $request->session()->has('guest_name')) {
            return to_route('home')->with('error', __('messages.access.required'));
        }

        $children = $profile->children()->orderBy('name')->get();
        $profileGifts = $profile->gifts()->with('reservedByProfile')->get();

        $gifts = $profileGifts->where('is_list', false)->values();
        $lists = $profileGifts->where('is_list', true)->values();

        return Inertia::render('Profiles/Show', [
            'profile' => $this->profilePayload($profile),
            'children' => $children
                ->sortBy(fn (Profile $child) => $this->profileSortKey($child))
                ->values()
                ->map(fn (Profile $child) => $this->profilePayload($child)),
            'otherProfiles' => Profile::query()
                ->whereKeyNot($profile->id)
                ->orderBy('name')
                ->get()
                ->sortBy(fn (Profile $other) => $this->profileSortKey($other))
                ->values()
                ->map(fn (Profile $other) => $this->profilePayload($other)),
            'gifts' => $gifts->map(fn ($gift) => $this->giftPayload($request, $gift)),
            'lists' => $lists->map(fn ($gift) => $this->giftPayload($request, $gift)),
            'permissions' => $this->permissionsFor($request, $profile),
        ]);
    }

    public function edit(Request $request, Profile $profile): Response
    {
        abort_unless($this->canManageProfile($request, $profile), 403);

        $profile->load('parents');

        return Inertia::render('Profiles/Form', [
            'profile' => [
                ...$this->profilePayload($profile),
                'parentIds' => $profile->parents->pluck('id')->values(),
            ],
            'parents' => $this->parentOptions($profile),
        ]);
    }

    public function update(ProfileRequest $request, Profile $profile): RedirectResponse
    {
        abort_unless($this->canManageProfile($request, $profile), 403);

        $data = $request->normalized();
        $parentIds = $data['parent_ids'];
        unset($data['parent_ids']);

        $profile->update($data);
        $profile->parents()->sync($data['is_child'] ? $parentIds : []);

        return to_route('profiles.show', $profile)->with('success', __('messages.profile.updated'));
    }

    public function destroy(Request $request, Profile $profile): RedirectResponse
    {
        abort_unless($this->canManageProfile($request, $profile), 403);

        $name = $profile->name;
        $profile->delete();

        if ($request->session()->get('active_profile_id') === $profile->id) {
            $request->session()->forget('active_profile_id');
        }

        return to_route('home')->with('success', __('messages.profile.deleted', ['name' => $name]));
    }

    private function parentOptions(?Profile $excluded = null)
    {
        return Profile::query()
            ->where('is_child', false)
            ->when($excluded, fn ($query) => $query->whereKeyNot($excluded->id))
            ->orderBy('name')
            ->get()
            ->sortBy(fn (Profile $profile) => $this->profileSortKey($profile))
            ->values()
            ->map(fn (Profile $profile) => [
                'id' => $profile->id,
                'name' => $profile->name,
                'avatarUrl' => $profile->avatar_url,
            ]);
    }

    private function profileSortKey(Profile $profile): string
    {
        return Str::lower(Str::ascii($profile->name));
    }

    private function profilePayload(Profile $profile): array
    {
        return [
            'id' => $profile->id,
            'name' => $profile->name,
            'isChild' => $profile->is_child,
            'avatar' => $profile->avatar,
            'avatarUrl' => $profile->avatar_url,
            'birthday' => $profile->birthday?->toDateString(),
            'displayBirthday' => $profile->display_birthday,
            'displayAge' => $profile->display_age,
            'sizeTop' => $profile->size_top,
            'sizeBottom' => $profile->size_bottom,
            'sizeFeet' => $profile->size_feet,
            'childrenCount' => $profile->children_count ?? null,
        ];
    }

    private function giftPayload(Request $request, Gift $gift): array
    {
        $activeProfile = $this->activeProfile($request);
        $reservedByProfile = $gift->reservedByProfile;

        return [
            'id' => $gift->id,
            'title' => $gift->title,
            'description' => $gift->description,
            'link' => $gift->link,
            'isList' => $gift->is_list,
            'isReserved' => $gift->is_reserved,
            'reservedByProfileId' => $gift->reserved_by_profile_id,
            'reservedByGuestName' => $gift->reserved_by_guest_name,
            'reservedByCurrentSession' => $gift->reserved_by_profile_id
                ? $activeProfile?->is($reservedByProfile) ?? false
                : $gift->reserved_by_guest_name === $request->session()->get('guest_name'),
            'reservedBy' => $reservedByProfile ? [
                'id' => $reservedByProfile->id,
                'name' => $reservedByProfile->name,
                'avatarUrl' => $reservedByProfile->avatar_url,
            ] : null,
        ];
    }
}
