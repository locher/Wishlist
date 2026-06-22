<?php

declare(strict_types=1);

use App\Models\Gift;
use App\Models\Profile;
use Illuminate\Database\QueryException;

it('rejects invalid profile scalar values at database level', function (): void {
    expect(fn () => Profile::factory()->create(['avatar' => 16]))
        ->toThrow(QueryException::class);

    expect(fn () => Profile::factory()->create(['birthday' => now()->addDay()->toDateString()]))
        ->toThrow(QueryException::class);

    expect(fn () => Profile::factory()->create(['birthday' => '1929-12-31']))
        ->toThrow(QueryException::class);
});

it('rejects invalid parent child relations at database level', function (): void {
    $parent = Profile::factory()->create();
    $otherParent = Profile::factory()->create();
    $child = Profile::factory()->child()->create();

    expect(fn () => $parent->children()->attach($parent))
        ->toThrow(QueryException::class);

    expect(fn () => $child->children()->attach(Profile::factory()->child()->create()))
        ->toThrow(QueryException::class);

    expect(fn () => $parent->children()->attach($otherParent))
        ->toThrow(QueryException::class);
});

it('rejects profile type changes that would invalidate existing relations', function (): void {
    $parent = Profile::factory()->create();
    $child = Profile::factory()->child()->create();
    $parent->children()->attach($child);

    expect(fn () => $parent->update(['is_child' => true]))
        ->toThrow(QueryException::class);

    expect(fn () => $child->update(['is_child' => false]))
        ->toThrow(QueryException::class);
});

it('rejects inconsistent gift reservations at database level', function (): void {
    $owner = Profile::factory()->create();
    $reserver = Profile::factory()->create();

    expect(fn () => Gift::factory()->for($owner)->create([
        'reserved_by_profile_id' => $reserver->id,
        'reserved_by_guest_name' => 'Guest Alice',
        'reserved_at' => now(),
    ]))->toThrow(QueryException::class);

    expect(fn () => Gift::factory()->for($owner)->create([
        'reserved_by_profile_id' => $reserver->id,
    ]))->toThrow(QueryException::class);

    expect(fn () => Gift::factory()->for($owner)->create([
        'reserved_at' => now(),
    ]))->toThrow(QueryException::class);

    expect(fn () => Gift::factory()->for($owner)->create([
        'reserved_by_guest_name' => '   ',
        'reserved_at' => now(),
    ]))->toThrow(QueryException::class);

    expect(fn () => Gift::factory()->for($owner)->list()->create([
        'reserved_by_guest_name' => 'Guest Alice',
        'reserved_at' => now(),
    ]))->toThrow(QueryException::class);
});
