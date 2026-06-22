<?php

declare(strict_types=1);

use App\Models\Gift;
use App\Models\Profile;
use Inertia\Testing\AssertableInertia as Assert;

it('shows the profile creation form with parent options', function (): void {
    Profile::factory()->create(['name' => 'Bob']);
    Profile::factory()->create(['name' => 'Alice']);
    Profile::factory()->child()->create(['name' => 'Charlie']);

    $this->get(route('profiles.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profiles/Form')
            ->where('profile', null)
            ->has('parents', 2)
            ->where('parents.0.name', 'Alice')
            ->where('parents.1.name', 'Bob')
        );
});

it('creates a child profile with parent relations', function (): void {
    $parent = Profile::factory()->create();

    $this->post(route('profiles.store'), [
        'name' => 'Charlie',
        'is_child' => true,
        'avatar' => 3,
        'birthday' => '2020-01-01',
        'parent_ids' => [$parent->id],
    ])->assertRedirect();

    $child = Profile::query()->where('name', 'Charlie')->firstOrFail();

    expect($child->is_child)->toBeTrue()
        ->and($child->parents()->whereKey($parent->id)->exists())->toBeTrue();
});

it('creates a parent profile without parent relations', function (): void {
    $parent = Profile::factory()->create();

    $this->post(route('profiles.store'), [
        'name' => 'Alice',
        'avatar' => 6,
        'birthday' => '1991-06-12',
        'size_top' => 'M',
        'size_bottom' => 'L',
        'size_feet' => '42',
        'parent_ids' => [$parent->id],
    ])->assertRedirect();

    $profile = Profile::query()->where('name', 'Alice')->firstOrFail();

    expect($profile->is_child)->toBeFalse()
        ->and($profile->parents)->toHaveCount(0)
        ->and($profile->size_top)->toBe('M')
        ->and($profile->size_bottom)->toBe('L')
        ->and($profile->size_feet)->toBe('42');
});

it('allows a parent profile to update a child profile', function (): void {
    $parent = Profile::factory()->create();
    $child = Profile::factory()->child()->create();
    $parent->children()->attach($child);

    $this->withSession(['active_profile_id' => $parent->id])
        ->put(route('profiles.update', $child), [
            'name' => 'Updated child',
            'is_child' => true,
            'avatar' => 4,
            'parent_ids' => [$parent->id],
        ])
        ->assertRedirect(route('profiles.show', $child));

    expect($child->refresh()->name)->toBe('Updated child');
});

it('allows an owner to update their own profile', function (): void {
    $profile = Profile::factory()->create();

    $this->withSession(['active_profile_id' => $profile->id])
        ->put(route('profiles.update', $profile), [
            'name' => 'Updated owner',
            'avatar' => 8,
            'birthday' => '1988-04-03',
        ])
        ->assertRedirect(route('profiles.show', $profile));

    expect($profile->refresh())
        ->name->toBe('Updated owner')
        ->avatar->toBe(8)
        ->is_child->toBeFalse();
});

it('forbids an unrelated profile from updating a profile', function (): void {
    $profile = Profile::factory()->create();
    $other = Profile::factory()->create();

    $this->withSession(['active_profile_id' => $other->id])
        ->put(route('profiles.update', $profile), [
            'name' => 'Forbidden update',
            'avatar' => 1,
        ])
        ->assertForbidden();

    expect($profile->refresh()->name)->not->toBe('Forbidden update');
});

it('shows the profile edit form with current parent links', function (): void {
    $parent = Profile::factory()->create(['name' => 'Alice']);
    Profile::factory()->create(['name' => 'Bob']);
    $child = Profile::factory()->child()->create(['name' => 'Charlie']);
    $parent->children()->attach($child);

    $this->withSession(['active_profile_id' => $parent->id])
        ->get(route('profiles.edit', $child))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profiles/Form')
            ->where('profile.name', 'Charlie')
            ->where('profile.parentIds.0', $parent->id)
            ->has('parents', 2)
            ->where('parents.0.name', 'Alice')
            ->where('parents.1.name', 'Bob')
        );
});

it('deletes a managed profile with its relations and gifts', function (): void {
    $parent = Profile::factory()->create();
    $child = Profile::factory()->child()->create();
    $parent->children()->attach($child);
    $gift = Gift::factory()->for($child)->create();

    $this->withSession(['active_profile_id' => $parent->id])
        ->delete(route('profiles.destroy', $child))
        ->assertRedirect(route('home'));

    expect(Profile::query()->whereKey($child->id)->exists())->toBeFalse()
        ->and(Gift::query()->whereKey($gift->id)->exists())->toBeFalse()
        ->and($parent->children()->whereKey($child->id)->exists())->toBeFalse();
});

it('clears the active profile session when deleting the active profile', function (): void {
    $profile = Profile::factory()->create();

    $this->withSession(['active_profile_id' => $profile->id])
        ->delete(route('profiles.destroy', $profile))
        ->assertRedirect(route('home'))
        ->assertSessionMissing('active_profile_id');
});

it('validates profile data boundaries', function (array $payload, string $field): void {
    $this->from(route('profiles.create'))
        ->post(route('profiles.store'), $payload)
        ->assertRedirect(route('profiles.create'))
        ->assertSessionHasErrors($field);
})->with([
    'required name' => [['avatar' => 1], 'name'],
    'avatar too low' => [['name' => 'Alice', 'avatar' => 0], 'avatar'],
    'avatar too high' => [['name' => 'Alice', 'avatar' => 16], 'avatar'],
    'future birthday' => [['name' => 'Alice', 'avatar' => 1, 'birthday' => '2999-01-01'], 'birthday'],
    'old birthday' => [['name' => 'Alice', 'avatar' => 1, 'birthday' => '1929-12-31'], 'birthday'],
]);

it('shows manageable child profiles on a parent profile page', function (): void {
    $parent = Profile::factory()->create();
    $child = Profile::factory()->child()->create(['name' => 'Dana']);
    $parent->children()->attach($child);

    $this->withSession(['active_profile_id' => $parent->id])
        ->get(route('profiles.show', $parent))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profiles/Show')
            ->has('children', 1)
            ->where('children.0.name', 'Dana')
            ->where('permissions.owner', true)
            ->where('permissions.canManage', true)
        );
});

it('does not mark a profile reservation as owned for a guest session', function (): void {
    $owner = Profile::factory()->create();
    $reservedBy = Profile::factory()->create(['name' => 'Bob']);
    Gift::factory()->for($owner)->create([
        'reserved_by_profile_id' => $reservedBy->id,
        'reserved_at' => now(),
    ]);

    $this->withSession(['guest_name' => 'Guest User'])
        ->get(route('profiles.show', $owner))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profiles/Show')
            ->where('gifts.0.isReserved', true)
            ->where('gifts.0.reservedBy.name', 'Bob')
            ->where('gifts.0.reservedByCurrentSession', false)
        );
});
