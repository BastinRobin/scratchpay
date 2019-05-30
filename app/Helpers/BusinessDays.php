<?php

namespace App\Helpers;
use App\Helpers\CustomCarbon as Carbon;


class BusinessDays {

    /**
     * Count all weekends
     * @param  array  $dates
     * @return interger
     */
    private static function countWeekendDays($start, $end) 
    {
        $start = $start->timestamp;
        $end = $end->timestamp;

        $iter = 24*60*60; // whole day in seconds
        $count = 0; // keep a count of Sats & Suns

        for($i = $start; $i <= $end; $i=$i+$iter)
        {
            if(Date('D',$i) == 'Sat' || Date('D',$i) == 'Sun')
            {
                $count++;
            }
        }
        return $count;
    }


    /**
     * Count total holidays
     * @param array $dates
     * @return integer $holidays
     */
    private static function countHolidays($dates) {

        $holidays = 0;

        foreach($dates as $date) {
            if ($date->isHoliday()) $holidays++; 
        }

        return $holidays;
    }

    /**
     * Count total holiday between two days
     */
    private static function seperateHolidayWeekend($fromDate, $endDate) 
    {

        $response = (object)['businessDays' => [], 'holidays' => []];

        $days = $fromDate->subDays(1)->diffInDaysFiltered(function (Carbon $date) use (&$response) {

            if ($date->isWeekday() &&  $date->isHoliday())
                array_push($response->businessDays, $date);
            else 
                array_push($response->holidays, $date);
            
        
            return $date->isWeekday() && !$date->isHoliday();
        
        }, $endDate);

        return $response;
        
    }


    public static function getDays($startDate, $delay) {

        
        // Add delays to the initalDate
        $initialDate = Carbon::create(date("Y-m-d h:i:s A T", strtotime($startDate)));
        $originalDate = $initialDate->copy();
       


        if ($initialDate->isWeekend() && $initialDate->isHoliday()) {
            
            $initialDate->next(Carbon::MONDAY);
            if ($initialDate->isHoliday) $initialDate->addDays(1);
            $delay = $delay - $initialDate->diffInDays($originalDate);
            
        }

        if (!$initialDate->isWeekend() && $initialDate->isHoliday()) {
            $initialDate->addWeekdays(1);
            if ($initialDate->isWeekend()) $initialDate->next(Carbon::MONDAY);
            $delay = $delay - $initialDate->diffInDays($originalDate) + 3;
            
        }

        if ($initialDate->isWeekend() && !$initialDate->isHoliday()) {
            $initialDate->next(Carbon::MONDAY);
            if ($initialDate->isHoliday()) $initialDate->addDays(1);
            $delay = $delay - 2;
            
        }
    
        if (!$initialDate->isWeekend() && !$initialDate->isHoliday()) {
            $initialDate = $initialDate;
            $delay = $delay + 1;
            
        }

        $settleDate = $initialDate->addDays($delay);

        $total_days = $originalDate->diffInDays($settleDate);

        $response = self::seperateHolidayWeekend($originalDate, $settleDate);

        return [
               
                'businessDate' => $settleDate,
                'weekendDays' => self::countWeekendDays($originalDate, $settleDate),
                'holidayDays' => self::countHolidays($response->holidays),
                'totalDays' => $total_days
        ];

    }

}

