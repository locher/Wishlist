# Functional Specification

## Product Vision

KDO is a family gift-list application. It lets people publish gift ideas, share external wishlists, reserve gifts without duplicates, and manage children lists from parent profiles.

The application keeps the historical no-password model. A user either selects a parent profile from the home page or enters a guest name to browse and reserve gifts.

## Actors

- **Connected parent**: an adult profile selected from the home page.
- **Child**: a non-connectable profile linked to one or more parents.
- **Guest**: a visitor identified by a free-form name.
- **Manager**: the owner of a profile or a parent linked to a child profile.

## Business Rules

- Only non-child profiles appear on the home page.
- Child profiles cannot connect directly.
- A parent can manage their own profile and linked child profiles.
- A guest can browse lists and reserve or cancel their own reservations.
- A gift can have only one active reservation.
- An external list is a gift marked as `is_list`; it is displayed separately and cannot be reserved.
- A manager does not see reservation actions on profiles they manage.
- Opening a profile page requires either a profile session or a guest session.
- Profiles and lists are sorted alphabetically with accent-normalized ordering.

## Main Data

### Profile

A profile contains a name, a parent/child flag, an avatar, an optional birth date, and optional sizing information: top, bottom, and shoe size.

### Parent-Child Relation

A relation allows a parent to manage a child profile. A child can be linked to multiple parents.

### Gift or External List

An item contains a title, an optional description, an optional link, and a type: reservable gift or external list.

### Reservation

A reservation references either a reserving profile or a guest name, plus a reservation date.

## User Journeys and Use Cases

### UC01 - Select a Parent Profile

**Actor**: Parent.  
**Trigger**: The parent clicks "Me connecter" from the home page.  
**Result**: The session stores `active_profile_id`, clears any guest name, and opens the selected profile page.

### UC02 - Access as Guest

**Actor**: Guest.  
**Trigger**: The guest opens guest access and enters a name.  
**Result**: The session stores `guest_name`, clears any active profile, and opens the profile index.

### UC03 - End Session

**Actor**: Parent or guest.  
**Result**: `active_profile_id` and `guest_name` are removed from the session, then the user returns to the home page.

### UC04 - Browse Profiles

**Actor**: Connected parent or guest.  
**Result**: All profiles are visible in alphabetical order, including child profiles.

### UC05 - View a Profile Page

**Actor**: Connected parent or guest.  
**Result**: The page displays the avatar, birthday, age, sizing information, external lists, gifts, and links to other profiles.

### UC06 - View Manageable Child Profiles

**Actor**: Connected parent.  
**Precondition**: The parent has at least one linked child.  
**Result**: The "Mes autres listes modifiables" section displays child profiles that the parent can manage.

### UC07 - Create a Parent Profile

**Actor**: Visitor or connected profile.  
**Data**: Required name and avatar, optional birth date and sizes.  
**Result**: A new parent profile is created and becomes available.

### UC08 - Create a Child Profile

**Actor**: Visitor or connected profile.  
**Data**: The profile is marked as a child and linked to the relevant parents.  
**Result**: The child does not appear on the home page, but appears in profile lists and under linked parents.

### UC09 - Edit Own Profile

**Actor**: Owner.  
**Result**: Name, avatar, birth date, sizes, and child status are updated.

### UC10 - Edit a Child Profile

**Actor**: Linked parent.  
**Result**: The parent can update the child profile and its parent links.

### UC11 - Delete a Profile

**Actor**: Manager.  
**Result**: The profile, its relations, and its gifts are deleted. If the deleted profile is the active profile, the profile session is cleared.

### UC12 - Create a Gift

**Actor**: Profile manager.  
**Data**: Required title, optional description and link.  
**Result**: The gift appears in "Mes cadeaux".

### UC13 - Create an External List

**Actor**: Profile manager.  
**Data**: Required title, recommended link, and `is_list` enabled.  
**Result**: The item appears in "Mes listes externes".

### UC14 - Edit a Gift or External List

**Actor**: Profile manager.  
**Result**: The item fields are updated.

### UC15 - Delete a Gift or External List

**Actor**: Profile manager.  
**Result**: The item is removed from the profile page.

### UC16 - Open an External List

**Actor**: Connected parent or guest.  
**Precondition**: The item has a link.  
**Result**: The user opens the external list on the third-party website.

### UC17 - Reserve a Gift as Profile

**Actor**: Connected parent.  
**Precondition**: The gift is not already reserved.  
**Result**: The reservation stores the reserving profile and hides the reservation action.

### UC18 - Reserve a Gift as Guest

**Actor**: Guest.  
**Precondition**: The gift is not already reserved.  
**Result**: The reservation stores the guest name.

### UC19 - Cancel Own Reservation

**Actor**: Reserving profile or guest.  
**Result**: The reservation is removed and the gift becomes reservable again.

### UC20 - Cancel a Reservation as Manager

**Actor**: Owner or linked parent.  
**Result**: The reservation is removed, even if it belongs to another profile or a guest.

### UC21 - Hide Reservation Actions from Managers

**Actor**: Owner or linked parent.  
**Result**: Reservation buttons are not displayed on gifts belonging to the managed profile.

### UC22 - Reject Concurrent Reservation

**Actor**: Parent or guest.  
**Precondition**: The gift is already reserved.  
**Result**: The application rejects the action and keeps the existing reservation.

### UC23 - Reject Unauthorized Management

**Actor**: Unrelated profile or guest.  
**Result**: The application returns an authorization error for unauthorized create, update, delete, or cancellation actions.

### UC24 - Import Former Production Data

**Actor**: Technical administrator.  
**Command**: `php artisan wishlist:import-legacy`.  
**Result**: Profiles, relations, and gifts are imported from the `legacy_mysql` connection. Internal former-system identifiers keep the import idempotent.

### UC25 - Replay a Local Dataset Snapshot

**Actor**: Developer.  
**File**: `database/seeders/production_snapshot.sql`, ignored by Git.  
**Result**: A local SQLite database can reproduce a private dataset without publishing it.

## Edge Cases

- A visitor without a session who opens a profile is redirected to the home page.
- A gift attached to another profile returns a 404 in nested gift routes.
- A future birth date or a birth date before 1930 is rejected.
- An invalid gift link is rejected.
- An avatar must be between 1 and 15.

## Quality Indicators

- Critical journeys are covered by Pest feature and unit tests.
- The UI keeps the former visual identity: white background, deep navy shape, illustrated avatars, large headings, and rounded buttons.
- The application remains usable on desktop and mobile.
