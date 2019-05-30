<?php

namespace App\Helpers;
use Carbon\Carbon;


class CustomCarbon extends Carbon {

    public function isHoliday() {
        return in_array($this->format('Y-m-d'), array_map(function($date) { return $date->format('Y-m-d'); }, [
            Carbon::create(2018, 1, 1), Carbon::create(2018, 1, 15),
            Carbon::create(2018, 2, 19), Carbon::create(2018, 5, 28),
            Carbon::create(2018, 7, 4),Carbon::create(2018, 9, 3),
            Carbon::create(2018, 10, 8),Carbon::create(2018, 11, 12),
            Carbon::create(2018, 11, 22),Carbon::create(2018, 12, 25),
            Carbon::create(2019, 1, 1),Carbon::create(2019, 1, 21),
            Carbon::create(2019, 2, 18), Carbon::create(2019, 5, 27),
            Carbon::create(2019, 7, 4), Carbon::create(2019, 9, 2),
            Carbon::create(2019, 10, 14), Carbon::create(2019, 11, 11),
            Carbon::create(2019, 11, 28), Carbon::create(2019, 12, 25),
            Carbon::create(2020, 1, 1), Carbon::create(2020, 1, 20),
            Carbon::create(2020, 2, 17), Carbon::create(2020, 5, 25),
            Carbon::create(2020, 7, 3), Carbon::create(2020, 9, 7),
            Carbon::create(2020, 10, 12), Carbon::create(2020, 11, 11),
            Carbon::create(2020, 11, 26), Carbon::create(2020, 12, 25)
        ]));
    }
}




?>