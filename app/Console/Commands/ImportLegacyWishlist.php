<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Gift;
use App\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLegacyWishlist extends Command
{
    protected $signature = 'wishlist:import-legacy
        {--users=KDO_peoples : Legacy users table}
        {--gifts=KDO_gifts : Legacy gifts table}
        {--parents=KDO_parents : Legacy parent-child relation table}';

    protected $description = 'Import legacy Wishlist data into the Laravel schema idempotently.';

    public function handle(): int
    {
        $legacy = DB::connection('legacy_mysql');

        DB::transaction(function () use ($legacy): void {
            $legacy->table($this->option('users'))
                ->orderBy('userID')
                ->get()
                ->each(function (object $row): void {
                    $profile = Profile::query()->where('legacy_id', $row->userID)->first() ?? new Profile;

                    $profile->forceFill([
                        'legacy_id' => $row->userID,
                        'name' => $row->name,
                        'is_child' => (bool) $row->isChildAccount,
                        'avatar' => max(1, min(15, (int) ($row->picture ?: 1))),
                        'birthday' => $row->birthday_date ?: null,
                        'size_top' => $row->size_top ?: null,
                        'size_bottom' => $row->size_bottom ?: null,
                        'size_feet' => $row->size_feet ?: null,
                    ])->save();
                });

            $legacy->table($this->option('parents'))
                ->orderBy('ID_parent')
                ->get()
                ->each(function (object $row): void {
                    $parent = Profile::query()->where('legacy_id', $row->ID_parent)->first();
                    $child = Profile::query()->where('legacy_id', $row->ID_child)->first();

                    if (! $parent || ! $child) {
                        $this->warn("Skipping legacy parent relation {$row->ID_parent} -> {$row->ID_child}: missing imported profile.");

                        return;
                    }

                    $parent->children()->syncWithoutDetaching([
                        $child->id => [
                            'legacy_parent_id' => $row->ID_parent,
                            'legacy_child_id' => $row->ID_child,
                        ],
                    ]);
                });

            $legacy->table($this->option('gifts'))
                ->orderBy('ID')
                ->get()
                ->each(function (object $row): void {
                    $profile = Profile::query()->where('legacy_id', $row->userID)->first();

                    if (! $profile) {
                        $this->warn("Skipping legacy gift {$row->ID}: missing owner profile {$row->userID}.");

                        return;
                    }

                    $reservedByProfile = null;
                    if (! empty($row->reservationUserID)) {
                        $reservedByProfile = Profile::query()
                            ->where('legacy_id', $row->reservationUserID)
                            ->value('id');
                    }

                    $gift = Gift::query()->where('legacy_id', $row->ID)->first() ?? new Gift;

                    $gift->forceFill([
                        'legacy_id' => $row->ID,
                        'profile_id' => $profile->id,
                        'title' => $row->title,
                        'description' => $row->description ?: null,
                        'link' => $row->link ?: null,
                        'is_list' => (bool) $row->isList,
                        'reserved_by_profile_id' => $reservedByProfile,
                        'reserved_by_guest_name' => $reservedByProfile ? null : ($row->reservationGuestName ?: null),
                        'reserved_at' => ((bool) $row->isReserved) ? now() : null,
                    ])->save();
                });
        });

        $this->info('Legacy wishlist import completed.');

        return self::SUCCESS;
    }
}
