<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Gift;
use App\Models\Profile;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;

class E2eSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create('fr_FR');
        $faker->seed(20260622);

        $alice = Profile::query()->create([
            'name' => 'Alice',
            'avatar' => $faker->numberBetween(1, 15),
            'birthday' => '1990-06-12',
            'size_top' => 'M',
            'size_bottom' => 'L',
            'size_feet' => '39',
        ]);

        $bob = Profile::query()->create([
            'name' => 'Bob',
            'avatar' => $faker->numberBetween(1, 15),
            'birthday' => '1989-02-10',
        ]);

        $charlie = Profile::query()->create([
            'name' => 'Charlie',
            'is_child' => true,
            'avatar' => $faker->numberBetween(1, 15),
            'birthday' => '2020-08-21',
        ]);

        $dana = Profile::query()->create([
            'name' => 'Dana',
            'is_child' => true,
            'avatar' => $faker->numberBetween(1, 15),
            'birthday' => '2023-03-04',
        ]);

        $bob->children()->attach([$charlie->id, $dana->id]);

        Gift::query()->create([
            'profile_id' => $alice->id,
            'title' => 'Wooden puzzle',
            'description' => $faker->sentence(),
        ]);

        Gift::query()->create([
            'profile_id' => $alice->id,
            'title' => 'Bookstore wishlist',
            'link' => 'https://example.com/liste-librairie',
            'is_list' => true,
        ]);

        Gift::query()->create([
            'profile_id' => $bob->id,
            'title' => 'Headphones',
            'description' => $faker->sentence(),
        ]);
    }
}
