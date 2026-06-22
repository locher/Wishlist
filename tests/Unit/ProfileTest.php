<?php

declare(strict_types=1);

use App\Models\Profile;
use Illuminate\Support\Carbon;

it('exposes parent child relations', function (): void {
    $parent = Profile::factory()->create();
    $child = Profile::factory()->child()->create();

    $parent->children()->attach($child);

    expect($parent->children()->whereKey($child->id)->exists())->toBeTrue()
        ->and($child->parents()->whereKey($parent->id)->exists())->toBeTrue();
});

it('formats age for children under two years in months', function (): void {
    Carbon::setTestNow('2026-06-21');

    $profile = Profile::factory()->create(['birthday' => '2025-06-21']);

    expect($profile->display_age)->toBe('12 mois');

    Carbon::setTestNow();
});

it('formats age in the active locale', function (): void {
    Carbon::setTestNow('2026-06-21');
    app()->setLocale('en');

    $profile = Profile::factory()->create(['birthday' => '2020-06-21']);

    expect($profile->display_age)->toBe('6 years');

    app()->setLocale('fr');
    Carbon::setTestNow();
});
