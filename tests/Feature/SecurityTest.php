<?php

declare(strict_types=1);

use App\Models\Gift;
use App\Models\Profile;

it('does not expose raw stored html in inertia responses', function (): void {
    $payload = '<script>alert("xss")</script>';
    $profile = Profile::factory()->create(['name' => $payload]);
    Gift::factory()->for($profile)->create([
        'title' => '<img src=x onerror=alert("xss")>',
        'description' => '<svg onload=alert("xss")>',
    ]);

    $this->withSession(['active_profile_id' => $profile->id])
        ->get(route('profiles.show', $profile))
        ->assertOk()
        ->assertDontSee($payload, false)
        ->assertDontSee('<img src=x onerror=alert("xss")>', false)
        ->assertDontSee('<svg onload=alert("xss")>', false);
});

it('ignores reservation fields injected through gift forms', function (): void {
    $owner = Profile::factory()->create();
    $attacker = Profile::factory()->create();

    $this->withSession(['active_profile_id' => $owner->id])
        ->post(route('profiles.gifts.store', $owner), [
            'title' => 'Train set',
            'reserved_by_profile_id' => $attacker->id,
            'reserved_by_guest_name' => 'Injected Guest',
            'reserved_at' => now()->toDateTimeString(),
        ])
        ->assertRedirect(route('profiles.show', $owner));

    $gift = Gift::query()->where('title', 'Train set')->firstOrFail();

    expect($gift->reserved_by_profile_id)->toBeNull()
        ->and($gift->reserved_by_guest_name)->toBeNull()
        ->and($gift->reserved_at)->toBeNull();
});

it('prevents object-level authorization bypass when cancelling another profile reservation', function (): void {
    $owner = Profile::factory()->create();
    $reserver = Profile::factory()->create();
    $attacker = Profile::factory()->create();
    $gift = Gift::factory()->for($owner)->create([
        'reserved_by_profile_id' => $reserver->id,
        'reserved_at' => now(),
    ]);

    $this->withSession(['active_profile_id' => $attacker->id])
        ->delete(route('profiles.gifts.cancel-reservation', [$owner, $gift]))
        ->assertForbidden();

    expect($gift->refresh()->reserved_by_profile_id)->toBe($reserver->id);
});

it('does not allow state changes through get requests', function (): void {
    $profile = Profile::factory()->create();
    $gift = Gift::factory()->for($profile)->create();

    $this->get(route('session.profile', $profile))->assertMethodNotAllowed();
    $this->get(route('profiles.gifts.store', $profile))->assertMethodNotAllowed();
    $this->get(route('profiles.gifts.reserve', [$profile, $gift]))->assertMethodNotAllowed();
    $this->get(route('profiles.gifts.cancel-reservation', [$profile, $gift]))->assertMethodNotAllowed();
});

it('does not leak internal legacy identifiers through inertia profile payloads', function (): void {
    $profile = Profile::factory()->create(['legacy_id' => 12345]);

    $this->withSession(['active_profile_id' => $profile->id])
        ->get(route('profiles.show', $profile))
        ->assertOk()
        ->assertDontSee('legacy_id')
        ->assertDontSee('legacyId');
});
