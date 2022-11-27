<?php

namespace App\Http\Traits;

trait GlobalTrait {

    public static function getHours()
    {
        // Fetch all the settings from the 'settings' table.
        $hours = [
            '09:00:00',
            '10:30:00',
            '12:00:00',
            '15:30:00',
            '17:00:00',
            '18:30:00',
            '20:00:00',
        ];
        return $hours;
    }
}
