<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiController extends Controller
{
    
    /**
     * Check if the given `$initialDate` is business day
     * @return boolean
     */
    private function isBusinessDay($initialDate)
    {
        $day = date("D", strtotime($initialDate));

        return ($day == 'Sat' || $day == 'Sun') ? FALSE : TRUE;
        
    }

    /**
     * Check if give day is Bank Holiday
     */
    private function isBankHoliday($settlement_date) {

        // We can also use API for this list for simplicity i kept an array ;)
        $bank_holidays = [Carbon::create('1/2/2012'), Carbon::create('1/16/2012'), Carbon::create('2/20/2012'), Carbon::create('5/28/2012'),
        Carbon::create('7/4/2012'), Carbon::create('9/3/2012'), Carbon::create('10/8/2012'), Carbon::create('11/12/2012'), Carbon::create('11/22/2012'),
        Carbon::create('12/25/2012'), Carbon::create('1/1/2013'), Carbon::create('1/21/2013'), Carbon::create('2/18/2013'), Carbon::create('5/27/2013'),
        Carbon::create('7/4/2013'), Carbon::create('9/2/2013'), Carbon::create('10/14/2013'), Carbon::create('11/11/2013'), Carbon::create('11/28/2013'),
        Carbon::create('12/25/2013'), Carbon::create('1/1/2014'), Carbon::create('1/20/2014'), Carbon::create('2/17/2014'), Carbon::create('5/26/2014'),
        Carbon::create('7/4/2014'), Carbon::create('9/1/2014'), Carbon::create('10/13/2014'), Carbon::create('11/11/2014'), Carbon::create('11/27/2014'),
        Carbon::create('12/25/2014'), Carbon::create('1/1/2015'), Carbon::create('1/19/2015'), Carbon::create('2/16/2015'), Carbon::create('5/25/2015'),
        Carbon::create('7/3/2015'), Carbon::create('9/7/2015'), Carbon::create('10/12/2015'), Carbon::create('11/11/2015'), Carbon::create('11/26/2015'),
        Carbon::create('12/25/2015'), Carbon::create('1/1/2016'), Carbon::create('1/18/2016'), Carbon::create('2/15/2016'), Carbon::create('5/30/2016'),
        Carbon::create('7/4/2016'), Carbon::create('9/5/2016'), Carbon::create('10/10/2016'), Carbon::create('11/11/2016'), Carbon::create('11/24/2016'),
        Carbon::create('12/25/2016'), Carbon::create('1/2/2017'), Carbon::create('1/16/2017'), Carbon::create('2/20/2017'), Carbon::create('5/29/2017'),
        Carbon::create('7/4/2017'), Carbon::create('9/4/2017'), Carbon::create('10/9/2017'), Carbon::create('11/10/2017'), Carbon::create('11/23/2017'),
        Carbon::create('12/25/2017'), Carbon::create('1/1/2018'), Carbon::create('1/15/2018'), Carbon::create('2/19/2018'), Carbon::create('5/28/2018'),
        Carbon::create('7/4/2018'), Carbon::create('9/3/2018'), Carbon::create('10/8/2018'), Carbon::create('11/12/2018'), Carbon::create('11/22/2018'),
        Carbon::create('12/25/2018'), Carbon::create('1/1/2019'), Carbon::create('1/21/2019'), Carbon::create('2/18/2019'), Carbon::create('5/27/2019'),
        Carbon::create('7/4/2019'), Carbon::create('9/2/2019'), Carbon::create('10/14/2019'), Carbon::create('11/11/2019'), Carbon::create('11/28/2019'),
        Carbon::create('12/25/2019'), Carbon::create('1/1/2020'), Carbon::create('1/20/2020'), Carbon::create('2/17/2020'), Carbon::create('5/25/2020'), Carbon::create('7/3/2020'),
        Carbon::create('9/7/2020'), Carbon::create('10/12/2020'), Carbon::create('11/11/2020'), Carbon::create('11/26/2020'), Carbon::create('12/25/2020')];
        return in_array(date("m/d/Y", strtotime($settlement_date)), $bank_holidays) ? TRUE: FALSE;
        

    }


    /**
     * Move one day to next
     */
    private function nextDay($settlement_date) {
        return $settlement_date->addDays(1);
    } 


    /**
     * Count total holiday between two days
     */
    private function seperateHolidayWeekend($fromDate, $endDate) {

        $bank_holidays = [
            Carbon::create(2018, 1, 1),
            Carbon::create(2018, 1, 15),
            Carbon::create(2018, 2, 19),
            Carbon::create(2018, 5, 28),
            Carbon::create(2018, 7, 4),
            Carbon::create(2018, 9, 3),
            Carbon::create(2018, 10, 8),
            Carbon::create(2018, 11, 12),
            Carbon::create(2018, 11, 22),
            Carbon::create(2018, 12, 25),
            Carbon::create(2019, 1, 1),
            Carbon::create(2019, 1, 21),
            Carbon::create(2019, 2, 18),
            Carbon::create(2019, 5, 27),
            Carbon::create(2019, 7, 4),
            Carbon::create(2019, 9, 2),
            Carbon::create(2019, 10, 14),
            Carbon::create(2019, 11, 11),
            Carbon::create(2019, 11, 28),
            Carbon::create(2019, 12, 25),
            Carbon::create(2020, 1, 1),
            Carbon::create(2020, 1, 20),
            Carbon::create(2020, 2, 17),
            Carbon::create(2020, 5, 25),
            Carbon::create(2020, 7, 3),
            Carbon::create(2020, 9, 7),
            Carbon::create(2020, 10, 12),
            Carbon::create(2020, 11, 11),
            Carbon::create(2020, 11, 26),
            Carbon::create(2020, 12, 25)
        ];

        $response = (object)['businessDay' => [], 'holiday' => []];

        $days = $fromDate->diffInDaysFiltered(function (Carbon $date) use ($bank_holidays, &$response) {

            ($date->isWeekday() && !in_array($date, $bank_holidays)) 
            ? array_push($response->businessDay, $date) : array_push($response->holiday, $date);
        
            return $date->isWeekday() && !in_array($date, $bank_holidays);
        
        }, $endDate);

        return $response;
        
    }



    /**
     * Business Settlement API
     */
    public function index(Request $request)
    {
        // Add delays to the initalDate
        $initialDate = Carbon::create(date("m/d/Y h:i:s A T", strtotime($request->get('initialDate'))));

        $settlement_date = $initialDate->copy()->addDays((int) $request->get('delay'));
        

        $response = $this->seperateHolidayWeekend($initialDate, $settlement_date);
        
       
        return response()->json($response);

    }
}
