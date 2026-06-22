<?php

declare(strict_types=1);

use App\Models\Profile;
use Inertia\Testing\AssertableInertia as Assert;

it('shows the guest access page', function (): void {
    $this->get(route('session.guest.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Guest')
            ->where('locale', 'fr')
            ->where('supportedLocales.0', 'fr')
            ->where('supportedLocales.1', 'en')
        );
});

it('stores the selected locale and shares it with inertia', function (): void {
    $this->from(route('home'))
        ->post(route('locale.update', 'en'))
        ->assertRedirect(route('home'))
        ->assertSessionHas('locale', 'en');

    $this->withSession(['locale' => 'en'])
        ->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->where('locale', 'en')
        );
});

it('falls back to french when the session locale is unsupported', function (): void {
    $this->withSession(['locale' => 'de'])
        ->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->where('locale', 'fr')
        );
});

it('selects a profile and opens the profile list', function (): void {
    $profile = Profile::factory()->create(['name' => 'Alice']);

    $this->post(route('session.profile', $profile))
        ->assertRedirect(route('profiles.show', $profile))
        ->assertSessionHas('active_profile_id', $profile->id)
        ->assertSessionHas('success', 'Profil Alice sélectionné.');
});

it('uses translated flash messages for english sessions', function (): void {
    $profile = Profile::factory()->create(['name' => 'Alice']);

    $this->withSession(['locale' => 'en'])
        ->post(route('session.profile', $profile))
        ->assertRedirect(route('profiles.show', $profile))
        ->assertSessionHas('success', 'Alice profile selected.');
});

it('does not allow selecting a child profile session', function (): void {
    $child = Profile::factory()->child()->create();

    $this->post(route('session.profile', $child))
        ->assertForbidden()
        ->assertSessionMissing('active_profile_id');
});

it('allows guest access to the profiles index', function (): void {
    $this->withSession(['active_profile_id' => Profile::factory()->create()->id])
        ->post(route('session.guest'), ['guest_name' => 'Camille'])
        ->assertRedirect(route('profiles.index'))
        ->assertSessionHas('guest_name', 'Camille')
        ->assertSessionMissing('active_profile_id');
});

it('destroys profile and guest sessions', function (): void {
    $profile = Profile::factory()->create();

    $this->withSession([
        'active_profile_id' => $profile->id,
        'guest_name' => 'Camille',
    ])
        ->delete(route('session.destroy'))
        ->assertRedirect(route('home'))
        ->assertSessionMissing('active_profile_id')
        ->assertSessionMissing('guest_name');
});

it('redirects list access when no profile or guest session exists', function (): void {
    $this->get(route('profiles.index'))
        ->assertRedirect(route('home'));
});

it('redirects profile access when no profile or guest session exists', function (): void {
    $profile = Profile::factory()->create();

    $this->get(route('profiles.show', $profile))
        ->assertRedirect(route('home'));
});

it('shows only parent profiles on the home page in accent-normalized order', function (): void {
    Profile::factory()->create(['name' => 'Zoey']);
    Profile::factory()->child()->create(['name' => 'Casey Child']);
    Profile::factory()->create(['name' => 'Écho']);
    Profile::factory()->create(['name' => 'Alex']);

    $this->get(route('home'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('profiles', 3)
            ->where('profiles.0.name', 'Alex')
            ->where('profiles.1.name', 'Écho')
            ->where('profiles.2.name', 'Zoey')
        );
});

it('shows every profile on the profile index in accent-normalized order', function (): void {
    $activeProfile = Profile::factory()->create(['name' => 'Bob']);
    Profile::factory()->child()->create(['name' => 'Écho Child']);
    Profile::factory()->create(['name' => 'Alice']);

    $this->withSession(['active_profile_id' => $activeProfile->id])
        ->get(route('profiles.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profiles/Index')
            ->has('profiles', 3)
            ->where('profiles.0.name', 'Alice')
            ->where('profiles.1.name', 'Bob')
            ->where('profiles.2.name', 'Écho Child')
        );
});
