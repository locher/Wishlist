<?php

declare(strict_types=1);

use App\Models\Gift;
use App\Models\Profile;
use Inertia\Testing\AssertableInertia as Assert;

it('allows an owner to create update and delete a gift', function (): void {
    $profile = Profile::factory()->create();

    $this->withSession(['active_profile_id' => $profile->id])
        ->post(route('profiles.gifts.store', $profile), [
            'title' => 'Vélo',
            'description' => 'Rouge',
            'link' => 'https://example.com/bike',
        ])
        ->assertRedirect(route('profiles.show', $profile));

    $gift = Gift::query()->where('title', 'Vélo')->firstOrFail();

    $this->withSession(['active_profile_id' => $profile->id])
        ->put(route('profiles.gifts.update', [$profile, $gift]), [
            'title' => 'Vélo bleu',
            'is_list' => false,
        ])
        ->assertRedirect(route('profiles.show', $profile));

    expect($gift->refresh()->title)->toBe('Vélo bleu');

    $this->withSession(['active_profile_id' => $profile->id])
        ->delete(route('profiles.gifts.destroy', [$profile, $gift]))
        ->assertRedirect(route('profiles.show', $profile));

    expect(Gift::query()->whereKey($gift->id)->exists())->toBeFalse();
});

it('creates and displays an external list separately from reservable gifts', function (): void {
    $profile = Profile::factory()->create();

    $this->withSession(['active_profile_id' => $profile->id])
        ->post(route('profiles.gifts.store', $profile), [
            'title' => 'Liste librairie',
            'link' => 'https://example.com/wishlist',
            'is_list' => true,
        ])
        ->assertRedirect(route('profiles.show', $profile));

    $this->withSession(['active_profile_id' => $profile->id])
        ->get(route('profiles.show', $profile))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profiles/Show')
            ->has('lists', 1)
            ->has('gifts', 0)
            ->where('lists.0.title', 'Liste librairie')
            ->where('lists.0.isList', true)
        );
});

it('allows profile and guest reservations and cancellations', function (): void {
    $owner = Profile::factory()->create();
    $reserver = Profile::factory()->create();
    $gift = Gift::factory()->for($owner)->create();

    $this->withSession(['active_profile_id' => $reserver->id])
        ->post(route('profiles.gifts.reserve', [$owner, $gift]))
        ->assertRedirect(route('profiles.show', $owner));

    expect($gift->refresh()->reserved_by_profile_id)->toBe($reserver->id);

    $this->withSession(['active_profile_id' => $reserver->id])
        ->delete(route('profiles.gifts.cancel-reservation', [$owner, $gift]))
        ->assertRedirect(route('profiles.show', $owner));

    expect($gift->refresh()->reserved_by_profile_id)->toBeNull();

    $this->flushSession();

    $this->withSession(['guest_name' => 'Guest Alice'])
        ->post(route('profiles.gifts.reserve', [$owner, $gift]))
        ->assertRedirect(route('profiles.show', $owner));

    expect($gift->refresh()->reserved_by_guest_name)->toBe('Guest Alice');
});

it('allows a manager to cancel another profile reservation', function (): void {
    $owner = Profile::factory()->create();
    $reserver = Profile::factory()->create();
    $gift = Gift::factory()->for($owner)->create([
        'reserved_by_profile_id' => $reserver->id,
        'reserved_at' => now(),
    ]);

    $this->withSession(['active_profile_id' => $owner->id])
        ->delete(route('profiles.gifts.cancel-reservation', [$owner, $gift]))
        ->assertRedirect(route('profiles.show', $owner));

    expect($gift->refresh()->is_reserved)->toBeFalse();
});

it('allows a guest to cancel only their own reservation', function (): void {
    $owner = Profile::factory()->create();
    $gift = Gift::factory()->for($owner)->create([
        'reserved_by_guest_name' => 'Guest Alice',
        'reserved_at' => now(),
    ]);

    $this->withSession(['guest_name' => 'Camille'])
        ->delete(route('profiles.gifts.cancel-reservation', [$owner, $gift]))
        ->assertForbidden();

    expect($gift->refresh()->reserved_by_guest_name)->toBe('Guest Alice');

    $this->withSession(['guest_name' => 'Guest Alice'])
        ->delete(route('profiles.gifts.cancel-reservation', [$owner, $gift]))
        ->assertRedirect(route('profiles.show', $owner));

    expect($gift->refresh()->is_reserved)->toBeFalse();
});

it('rejects concurrent reservations and keeps the original reservation', function (): void {
    $owner = Profile::factory()->create();
    $firstReserver = Profile::factory()->create();
    $secondReserver = Profile::factory()->create();
    $gift = Gift::factory()->for($owner)->create([
        'reserved_by_profile_id' => $firstReserver->id,
        'reserved_at' => now(),
    ]);

    $this->withSession(['active_profile_id' => $secondReserver->id])
        ->post(route('profiles.gifts.reserve', [$owner, $gift]))
        ->assertRedirect(route('profiles.show', $owner))
        ->assertSessionHas('error', __('messages.gift.already_reserved'));

    expect($gift->refresh()->reserved_by_profile_id)->toBe($firstReserver->id);
});

it('rejects reservation attempts on external lists', function (): void {
    $owner = Profile::factory()->create();
    $reserver = Profile::factory()->create();
    $list = Gift::factory()->for($owner)->list()->create();

    $this->withSession(['active_profile_id' => $reserver->id])
        ->post(route('profiles.gifts.reserve', [$owner, $list]))
        ->assertRedirect(route('profiles.show', $owner))
        ->assertSessionHas('error', __('messages.gift.list_not_reservable'));

    expect($list->refresh()->is_reserved)->toBeFalse();
});

it('hides reservation actions from profile managers in the page permissions', function (): void {
    $owner = Profile::factory()->create();
    Gift::factory()->for($owner)->create(['title' => 'Vélo']);

    $this->withSession(['active_profile_id' => $owner->id])
        ->get(route('profiles.show', $owner))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profiles/Show')
            ->where('permissions.canManage', true)
            ->where('permissions.guest', false)
            ->has('gifts', 1)
        );
});

it('forbids a profile from managing another profile gifts', function (): void {
    $owner = Profile::factory()->create();
    $other = Profile::factory()->create();

    $this->withSession(['active_profile_id' => $other->id])
        ->post(route('profiles.gifts.store', $owner), ['title' => 'Console'])
        ->assertForbidden();
});

it('returns not found when a gift does not belong to the route profile', function (): void {
    $owner = Profile::factory()->create();
    $other = Profile::factory()->create();
    $gift = Gift::factory()->for($other)->create();

    $this->withSession(['active_profile_id' => $owner->id])
        ->put(route('profiles.gifts.update', [$owner, $gift]), [
            'title' => 'Wrong profile',
            'is_list' => false,
        ])
        ->assertNotFound();
});

it('validates gift data', function (array $payload, string $field): void {
    $profile = Profile::factory()->create();

    $this->withSession(['active_profile_id' => $profile->id])
        ->from(route('profiles.show', $profile))
        ->post(route('profiles.gifts.store', $profile), $payload)
        ->assertRedirect(route('profiles.show', $profile))
        ->assertSessionHasErrors($field);
})->with([
    'required title' => [[], 'title'],
    'invalid link' => [['title' => 'Livre', 'link' => 'not-a-url'], 'link'],
]);
