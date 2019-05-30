<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\CustomCarbon as Carbon;
use App\Helpers\BusinessDays;
// use Carbon\Carbon;
// use Cmixin\BusinessDay;

// $additionalHolidays = [
//     '2018-1-1','2018-1-15',
//     '2018-2-19','2018-5-28',
//     '2018-7-4','2018-9-3',
//     '2018-10-8','2018-11-12',
//     '2018-11-22','2018-12-25',
//     '2019-1-1','2019-1-21',
//     '2019-2-18','2019-5-27',
//     '2019-7-4','2019-9-2',
//     '2019-10-14','2019-11-11',
//     '2019-11-28','2019-12-25',
//     '2020-1-1','2020-1-20',
//     '2020-2-17','2020-5-25',
//     '2020-7-3','2020-9-7',
//     '2020-10-12','2020-11-11',
//     '2020-11-26','2020-12-25'
// ];


// BusinessDay::enable('Illuminate\Support\Carbon','us-national', $additionalHolidays);
// Carbon::setHolidaysRegion('us');




class ApiController extends Controller
{


   
    /**
     * Check if the given `$initialDate` is business day
     * @return boolean
     */
    public function isBusinessDay(Request $request)
    {
        $date = Carbon::create(date("Y-m-d h:i:s A T", strtotime($request->get('initialDate'))));
    
        return response()
            ->json(($date->isWeekday() && !$date->isHoliday()) ? TRUE : FALSE, 200);
        
    }


    /**
     * Business Settled API 
     * @method `GET`
     * @param REQUEST $request
     * @return json response
     */
    public function getBusinessDates(Request $request)
    {
        return response()
            ->json([
                "ok" => true,
                "initialQuery" => $request->input(),
                "results" => BusinessDays::getDays($request->initialDate, $request->delay)
            ]);
    }

    /**
     * Business Settlement API
     * @method `POST`
     * @param REQUEST $request
     * @return json response
     */
    public function postBusinessDates(Request $request)
    {
        
        return response()
            ->json([
                "ok" => true,
                "initialQuery" => $request->input(),
                "results" => BusinessDays::getDays($request->initialDate, $request->delay)
            ]);

    }

}
