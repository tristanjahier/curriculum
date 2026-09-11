<?php

namespace App\Observers;

use App\Models\Person;
use App\Support\PersonPhoto;

class PersonObserver
{
    /**
     * Handle the Person "updated" event.
     */
    public function updated(Person $person): void
    {
        if ($person->wasChanged('photo')) {
            $original = $person->getOriginal('photo');

            if (isset($original)) {
                PersonPhoto::delete($original);
            }
        }
    }

    /**
     * Handle the Person "deleted" event.
     */
    public function deleted(Person $person): void
    {
        if (isset($person->photo)) {
            PersonPhoto::delete($person->photo);
        }
    }
}
