<?php

use App\Models\Person;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Storage;

test('full_name accessor returns first and last name concatenated', function () {
    $person = Person::factory()->make(['first_name' => 'Ada', 'last_name' => 'Lovelace']);

    expect($person->full_name)->toBe('Ada Lovelace');
});

test('born_at accessor returns the exact birth timestamp with the right timezone', function () {
    $person = Person::factory()->make(['birth_datetime' => '2015-09-17 04:50:06', 'birth_timezone' => 'Asia/Gaza']);

    expect($person->born_at)->toBeInstanceOf(CarbonInterface::class);
    expect($person->born_at->toIso8601String())
        ->toBe('2015-09-17T04:50:06+03:00');
    expect($person->born_at->getTimezone()->getName())->toBe('Asia/Gaza');
});

test('age accessor returns the age (truncated to whole years) taking into account the birth timezone', function () {
    $this->travelTo(CarbonImmutable::parse('2026-08-28 19:47:00', 'UTC'));

    $birth = now('America/New_York')->subYears(30)->addHour();

    $person = Person::factory()->make([
        'birth_datetime' => $birth->toDateTimeString(),
        'birth_timezone' => $birth->getTimezone()->getName(),
    ]);

    // Application timezone is UTC, so America/New_York is 4 hours behind in face value.
    // e.g. UTC: 19:47 => New York: 15:47.
    // $person is one hour from turning 30, but in clock face value it naively looks like they are already 30.
    // A real diff between timestamps must give the correct result which is still 29 years old.
    expect($person->age)->toBe(29);
});

test('updating birth_datetime or birth_timezone updates born_at', function () {
    $person = Person::factory()->make(['birth_datetime' => '2015-09-17 04:50:06', 'birth_timezone' => 'Asia/Gaza']);

    expect($person->born_at->toIso8601String())->toBe('2015-09-17T04:50:06+03:00');
    expect($person->born_at->getTimezone()->getName())->toBe('Asia/Gaza');

    $person->birth_datetime = '2013-05-05 13:16:29';

    expect($person->born_at->toIso8601String())->toBe('2013-05-05T13:16:29+03:00');
    expect($person->born_at->getTimezone()->getName())->toBe('Asia/Gaza');

    $person->birth_timezone = 'Europe/Paris';

    expect($person->born_at->toIso8601String())->toBe('2013-05-05T13:16:29+02:00');
    expect($person->born_at->getTimezone()->getName())->toBe('Europe/Paris');
});

test('updating the photo deletes the former', function () {
    Storage::fake('public');
    Storage::disk('public')->put('photos/mnf87n34bndgud7869vhkj4w5.jpg', random_bytes(64));
    Storage::disk('public')->assertExists('photos/mnf87n34bndgud7869vhkj4w5.jpg');

    $person = Person::factory()->create(['photo' => 'mnf87n34bndgud7869vhkj4w5.jpg']);

    $person->update(['photo' => 'j7f73n459fgd82dnm0cl4n4m.jpg']);

    Storage::disk('public')->assertMissing('photos/mnf87n34bndgud7869vhkj4w5.jpg');
});

test('updating any attribute except the photo preserves the photo', function () {
    Storage::fake('public');
    Storage::disk('public')->put('photos/mnf87n34bndgud7869vhkj4w5.jpg', random_bytes(64));
    Storage::disk('public')->assertExists('photos/mnf87n34bndgud7869vhkj4w5.jpg');

    $person = Person::factory()->create(['photo' => 'mnf87n34bndgud7869vhkj4w5.jpg']);

    $person->update(['first_name' => 'Toto']);

    Storage::disk('public')->assertExists('photos/mnf87n34bndgud7869vhkj4w5.jpg');
});

test('deleting a person deletes their photo', function () {
    Storage::fake('public');
    Storage::disk('public')->put('photos/JSDF7DFN45DS87FDG.jpg', random_bytes(64));
    Storage::disk('public')->assertExists('photos/JSDF7DFN45DS87FDG.jpg');

    $person = Person::factory()->create(['photo' => 'JSDF7DFN45DS87FDG.jpg']);

    $person->delete();

    Storage::disk('public')->assertMissing('photos/JSDF7DFN45DS87FDG.jpg');
});

test('deleting a person without a photo leaves others untouched', function () {
    Storage::fake('public');
    Storage::disk('public')->put('photos/NSDFD89DSF6B675DJ12HK3.jpg', random_bytes(64));

    $person = Person::factory()->create(['photo' => null]);

    $person->delete();

    Storage::disk('public')->assertExists('photos/NSDFD89DSF6B675DJ12HK3.jpg');
});
