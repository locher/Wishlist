<?php

declare(strict_types=1);

use App\Models\Gift;
use App\Models\Profile;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

it('imports legacy data idempotently', function (): void {
    Config::set('database.connections.legacy_testing', [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ]);
    Config::set('database.connections.legacy_mysql', Config::get('database.connections.legacy_testing'));

    $legacy = DB::connection('legacy_mysql');

    $legacy->getSchemaBuilder()->create('KDO_peoples', function (Blueprint $table): void {
        $table->increments('userID');
        $table->string('name');
        $table->boolean('isChildAccount');
        $table->integer('picture')->nullable();
        $table->date('birthday_date')->nullable();
        $table->string('size_top')->nullable();
        $table->string('size_bottom')->nullable();
        $table->string('size_feet')->nullable();
    });
    $legacy->getSchemaBuilder()->create('KDO_parents', function (Blueprint $table): void {
        $table->integer('ID_parent');
        $table->integer('ID_child');
    });
    $legacy->getSchemaBuilder()->create('KDO_gifts', function (Blueprint $table): void {
        $table->increments('ID');
        $table->integer('userID');
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('link')->nullable();
        $table->boolean('isList');
        $table->boolean('isReserved');
        $table->integer('reservationUserID')->nullable();
        $table->string('reservationGuestName')->nullable();
    });

    $legacy->table('KDO_peoples')->insert([
        ['userID' => 1, 'name' => 'Parent', 'isChildAccount' => false, 'picture' => 1],
        ['userID' => 2, 'name' => 'Child', 'isChildAccount' => true, 'picture' => 2],
    ]);
    $legacy->table('KDO_parents')->insert([
        ['ID_parent' => 1, 'ID_child' => 2],
        ['ID_parent' => 999, 'ID_child' => 2],
        ['ID_parent' => 1, 'ID_child' => 999],
    ]);
    $legacy->table('KDO_gifts')->insert([
        [
            'ID' => 10,
            'userID' => 2,
            'title' => 'Book',
            'description' => null,
            'link' => null,
            'isList' => false,
            'isReserved' => false,
            'reservationUserID' => null,
            'reservationGuestName' => null,
        ],
        [
            'ID' => 11,
            'userID' => 2,
            'title' => 'Reserved toy',
            'description' => null,
            'link' => null,
            'isList' => false,
            'isReserved' => true,
            'reservationUserID' => 1,
            'reservationGuestName' => null,
        ],
        [
            'ID' => 12,
            'userID' => 999,
            'title' => 'Orphan gift',
            'description' => null,
            'link' => null,
            'isList' => false,
            'isReserved' => false,
            'reservationUserID' => null,
            'reservationGuestName' => null,
        ],
    ]);

    $this->artisan('wishlist:import-legacy')
        ->expectsOutputToContain('Skipping legacy parent relation 999 -> 2: missing imported profile.')
        ->expectsOutputToContain('Skipping legacy parent relation 1 -> 999: missing imported profile.')
        ->expectsOutputToContain('Skipping legacy gift 12: missing owner profile 999.')
        ->assertSuccessful();
    $this->artisan('wishlist:import-legacy')->assertSuccessful();

    $parent = Profile::query()->where('legacy_id', 1)->firstOrFail();
    $child = Profile::query()->where('legacy_id', 2)->firstOrFail();
    $reservedGift = Gift::query()->where('legacy_id', 11)->firstOrFail();

    expect(Profile::query()->count())->toBe(2)
        ->and(Gift::query()->count())->toBe(2)
        ->and($parent->children()->whereKey($child->id)->exists())->toBeTrue()
        ->and($reservedGift->reserved_by_profile_id)->toBe($parent->id)
        ->and($reservedGift->reserved_by_guest_name)->toBeNull()
        ->and($reservedGift->reserved_at)->not->toBeNull();

    Schema::connection('legacy_mysql')->dropAllTables();
});
