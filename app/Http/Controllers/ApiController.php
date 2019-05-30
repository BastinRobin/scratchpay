<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

use App\Helpers\CustomCarbon as Carbon;
use App\Helpers\BusinessDays;



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




    public function getDates(Request $request) {


        $response = [
            "ok" => true,
            "initialQuery" => $request->input(),
            "results" => BusinessDays::getDays($request->initialDate, $request->delay)
        ];

        Redis::publish('BankWire', json_encode($response));

        return response()
                ->json($response, 200);
    }
}
