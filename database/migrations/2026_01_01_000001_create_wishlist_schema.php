<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->unique();
            $table->string('name');
            $table->boolean('is_child')->default(false);
            $table->unsignedTinyInteger('avatar')->default(1);
            $table->date('birthday')->nullable();
            $table->string('size_top')->nullable();
            $table->string('size_bottom')->nullable();
            $table->string('size_feet')->nullable();
            $table->timestamps();
        });

        Schema::create('profile_relations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('legacy_parent_id')->nullable();
            $table->unsignedBigInteger('legacy_child_id')->nullable();
            $table->foreignId('parent_id')->constrained('profiles')->cascadeOnDelete();
            $table->foreignId('child_id')->constrained('profiles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['parent_id', 'child_id']);
        });

        Schema::create('gifts', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->unique();
            $table->foreignId('profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->boolean('is_list')->default(false);
            $table->foreignId('reserved_by_profile_id')->nullable()->constrained('profiles')->nullOnDelete();
            $table->string('reserved_by_guest_name')->nullable();
            $table->timestamp('reserved_at')->nullable();
            $table->timestamps();

            $table->index(['profile_id', 'is_list']);
            $table->index('reserved_by_profile_id');
            $table->index('reserved_by_guest_name');
        });

        $this->installValidationTriggers();
    }

    public function down(): void
    {
        $this->dropValidationTriggers();

        Schema::dropIfExists('gifts');
        Schema::dropIfExists('profile_relations');
        Schema::dropIfExists('profiles');
    }

    private function installValidationTriggers(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $this->installSqliteValidationTriggers();

            return;
        }

        if (DB::getDriverName() === 'mysql') {
            $this->installMysqlValidationTriggers();
        }
    }

    private function dropValidationTriggers(): void
    {
        $triggers = [
            'profiles_validate_insert',
            'profiles_validate_update',
            'profile_relations_validate_insert',
            'profile_relations_validate_update',
            'profiles_validate_type_update',
            'gifts_validate_insert',
            'gifts_validate_update',
        ];

        foreach ($triggers as $trigger) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$trigger}");
        }
    }

    private function installSqliteValidationTriggers(): void
    {
        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profiles_validate_insert
            BEFORE INSERT ON profiles
            BEGIN
                SELECT CASE
                    WHEN NEW.avatar NOT BETWEEN 1 AND 15
                    THEN RAISE(ABORT, 'profiles.avatar must be between 1 and 15')
                END;
                SELECT CASE
                    WHEN NEW.birthday IS NOT NULL
                        AND (NEW.birthday < '1930-01-01' OR NEW.birthday > date('now'))
                    THEN RAISE(ABORT, 'profiles.birthday must be between 1930-01-01 and today')
                END;
            END;
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profiles_validate_update
            BEFORE UPDATE ON profiles
            BEGIN
                SELECT CASE
                    WHEN NEW.avatar NOT BETWEEN 1 AND 15
                    THEN RAISE(ABORT, 'profiles.avatar must be between 1 and 15')
                END;
                SELECT CASE
                    WHEN NEW.birthday IS NOT NULL
                        AND (NEW.birthday < '1930-01-01' OR NEW.birthday > date('now'))
                    THEN RAISE(ABORT, 'profiles.birthday must be between 1930-01-01 and today')
                END;
            END;
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profile_relations_validate_insert
            BEFORE INSERT ON profile_relations
            BEGIN
                SELECT CASE
                    WHEN NEW.parent_id = NEW.child_id
                    THEN RAISE(ABORT, 'profile relation cannot point to the same profile')
                END;
                SELECT CASE
                    WHEN (SELECT is_child FROM profiles WHERE id = NEW.parent_id) != 0
                    THEN RAISE(ABORT, 'profile relation parent must be a parent profile')
                END;
                SELECT CASE
                    WHEN (SELECT is_child FROM profiles WHERE id = NEW.child_id) != 1
                    THEN RAISE(ABORT, 'profile relation child must be a child profile')
                END;
            END;
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profile_relations_validate_update
            BEFORE UPDATE ON profile_relations
            BEGIN
                SELECT CASE
                    WHEN NEW.parent_id = NEW.child_id
                    THEN RAISE(ABORT, 'profile relation cannot point to the same profile')
                END;
                SELECT CASE
                    WHEN (SELECT is_child FROM profiles WHERE id = NEW.parent_id) != 0
                    THEN RAISE(ABORT, 'profile relation parent must be a parent profile')
                END;
                SELECT CASE
                    WHEN (SELECT is_child FROM profiles WHERE id = NEW.child_id) != 1
                    THEN RAISE(ABORT, 'profile relation child must be a child profile')
                END;
            END;
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profiles_validate_type_update
            BEFORE UPDATE OF is_child ON profiles
            BEGIN
                SELECT CASE
                    WHEN NEW.is_child = 1
                        AND EXISTS (SELECT 1 FROM profile_relations WHERE parent_id = NEW.id)
                    THEN RAISE(ABORT, 'parent profile cannot become a child while it manages children')
                END;
                SELECT CASE
                    WHEN NEW.is_child = 0
                        AND EXISTS (SELECT 1 FROM profile_relations WHERE child_id = NEW.id)
                    THEN RAISE(ABORT, 'child profile cannot become a parent while it has parents')
                END;
            END;
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER gifts_validate_insert
            BEFORE INSERT ON gifts
            BEGIN
                SELECT CASE
                    WHEN NEW.is_list = 1
                        AND (NEW.reserved_by_profile_id IS NOT NULL
                            OR NEW.reserved_by_guest_name IS NOT NULL
                            OR NEW.reserved_at IS NOT NULL)
                    THEN RAISE(ABORT, 'external lists cannot be reserved')
                END;
                SELECT CASE
                    WHEN NEW.reserved_by_profile_id IS NOT NULL
                        AND NEW.reserved_by_guest_name IS NOT NULL
                    THEN RAISE(ABORT, 'gift reservation must have only one reserver')
                END;
                SELECT CASE
                    WHEN (NEW.reserved_by_profile_id IS NOT NULL
                            OR NEW.reserved_by_guest_name IS NOT NULL)
                        AND NEW.reserved_at IS NULL
                    THEN RAISE(ABORT, 'gift reservation must have a reservation date')
                END;
                SELECT CASE
                    WHEN NEW.reserved_at IS NOT NULL
                        AND NEW.reserved_by_profile_id IS NULL
                        AND NEW.reserved_by_guest_name IS NULL
                    THEN RAISE(ABORT, 'gift reservation date requires a reserver')
                END;
                SELECT CASE
                    WHEN NEW.reserved_by_guest_name IS NOT NULL
                        AND length(trim(NEW.reserved_by_guest_name)) = 0
                    THEN RAISE(ABORT, 'guest reservation name cannot be blank')
                END;
            END;
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER gifts_validate_update
            BEFORE UPDATE ON gifts
            BEGIN
                SELECT CASE
                    WHEN NEW.is_list = 1
                        AND (NEW.reserved_by_profile_id IS NOT NULL
                            OR NEW.reserved_by_guest_name IS NOT NULL
                            OR NEW.reserved_at IS NOT NULL)
                    THEN RAISE(ABORT, 'external lists cannot be reserved')
                END;
                SELECT CASE
                    WHEN NEW.reserved_by_profile_id IS NOT NULL
                        AND NEW.reserved_by_guest_name IS NOT NULL
                    THEN RAISE(ABORT, 'gift reservation must have only one reserver')
                END;
                SELECT CASE
                    WHEN (NEW.reserved_by_profile_id IS NOT NULL
                            OR NEW.reserved_by_guest_name IS NOT NULL)
                        AND NEW.reserved_at IS NULL
                    THEN RAISE(ABORT, 'gift reservation must have a reservation date')
                END;
                SELECT CASE
                    WHEN NEW.reserved_at IS NOT NULL
                        AND NEW.reserved_by_profile_id IS NULL
                        AND NEW.reserved_by_guest_name IS NULL
                    THEN RAISE(ABORT, 'gift reservation date requires a reserver')
                END;
                SELECT CASE
                    WHEN NEW.reserved_by_guest_name IS NOT NULL
                        AND length(trim(NEW.reserved_by_guest_name)) = 0
                    THEN RAISE(ABORT, 'guest reservation name cannot be blank')
                END;
            END;
            SQL);
    }

    private function installMysqlValidationTriggers(): void
    {
        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profiles_validate_insert
            BEFORE INSERT ON profiles
            FOR EACH ROW
            BEGIN
                IF NEW.avatar NOT BETWEEN 1 AND 15 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profiles.avatar must be between 1 and 15';
                END IF;
                IF NEW.birthday IS NOT NULL
                    AND (NEW.birthday < '1930-01-01' OR NEW.birthday > CURRENT_DATE()) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profiles.birthday must be between 1930-01-01 and today';
                END IF;
            END
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profiles_validate_update
            BEFORE UPDATE ON profiles
            FOR EACH ROW
            BEGIN
                IF NEW.avatar NOT BETWEEN 1 AND 15 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profiles.avatar must be between 1 and 15';
                END IF;
                IF NEW.birthday IS NOT NULL
                    AND (NEW.birthday < '1930-01-01' OR NEW.birthday > CURRENT_DATE()) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profiles.birthday must be between 1930-01-01 and today';
                END IF;
            END
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profile_relations_validate_insert
            BEFORE INSERT ON profile_relations
            FOR EACH ROW
            BEGIN
                IF NEW.parent_id = NEW.child_id THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profile relation cannot point to the same profile';
                END IF;
                IF (SELECT is_child FROM profiles WHERE id = NEW.parent_id) != 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profile relation parent must be a parent profile';
                END IF;
                IF (SELECT is_child FROM profiles WHERE id = NEW.child_id) != 1 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profile relation child must be a child profile';
                END IF;
            END
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profile_relations_validate_update
            BEFORE UPDATE ON profile_relations
            FOR EACH ROW
            BEGIN
                IF NEW.parent_id = NEW.child_id THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profile relation cannot point to the same profile';
                END IF;
                IF (SELECT is_child FROM profiles WHERE id = NEW.parent_id) != 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profile relation parent must be a parent profile';
                END IF;
                IF (SELECT is_child FROM profiles WHERE id = NEW.child_id) != 1 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'profile relation child must be a child profile';
                END IF;
            END
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER profiles_validate_type_update
            BEFORE UPDATE ON profiles
            FOR EACH ROW
            BEGIN
                IF NEW.is_child = 1
                    AND EXISTS (SELECT 1 FROM profile_relations WHERE parent_id = NEW.id) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'parent profile cannot become a child while it manages children';
                END IF;
                IF NEW.is_child = 0
                    AND EXISTS (SELECT 1 FROM profile_relations WHERE child_id = NEW.id) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'child profile cannot become a parent while it has parents';
                END IF;
            END
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER gifts_validate_insert
            BEFORE INSERT ON gifts
            FOR EACH ROW
            BEGIN
                IF NEW.is_list = 1
                    AND (NEW.reserved_by_profile_id IS NOT NULL
                        OR NEW.reserved_by_guest_name IS NOT NULL
                        OR NEW.reserved_at IS NOT NULL) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'external lists cannot be reserved';
                END IF;
                IF NEW.reserved_by_profile_id IS NOT NULL
                    AND NEW.reserved_by_guest_name IS NOT NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'gift reservation must have only one reserver';
                END IF;
                IF (NEW.reserved_by_profile_id IS NOT NULL
                        OR NEW.reserved_by_guest_name IS NOT NULL)
                    AND NEW.reserved_at IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'gift reservation must have a reservation date';
                END IF;
                IF NEW.reserved_at IS NOT NULL
                    AND NEW.reserved_by_profile_id IS NULL
                    AND NEW.reserved_by_guest_name IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'gift reservation date requires a reserver';
                END IF;
                IF NEW.reserved_by_guest_name IS NOT NULL
                    AND CHAR_LENGTH(TRIM(NEW.reserved_by_guest_name)) = 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'guest reservation name cannot be blank';
                END IF;
            END
            SQL);

        DB::unprepared(<<<'SQL'
            CREATE TRIGGER gifts_validate_update
            BEFORE UPDATE ON gifts
            FOR EACH ROW
            BEGIN
                IF NEW.is_list = 1
                    AND (NEW.reserved_by_profile_id IS NOT NULL
                        OR NEW.reserved_by_guest_name IS NOT NULL
                        OR NEW.reserved_at IS NOT NULL) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'external lists cannot be reserved';
                END IF;
                IF NEW.reserved_by_profile_id IS NOT NULL
                    AND NEW.reserved_by_guest_name IS NOT NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'gift reservation must have only one reserver';
                END IF;
                IF (NEW.reserved_by_profile_id IS NOT NULL
                        OR NEW.reserved_by_guest_name IS NOT NULL)
                    AND NEW.reserved_at IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'gift reservation must have a reservation date';
                END IF;
                IF NEW.reserved_at IS NOT NULL
                    AND NEW.reserved_by_profile_id IS NULL
                    AND NEW.reserved_by_guest_name IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'gift reservation date requires a reserver';
                END IF;
                IF NEW.reserved_by_guest_name IS NOT NULL
                    AND CHAR_LENGTH(TRIM(NEW.reserved_by_guest_name)) = 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'guest reservation name cannot be blank';
                END IF;
            END
            SQL);
    }
};
