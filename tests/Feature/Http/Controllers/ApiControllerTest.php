<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function testIsBusinessDay()
    {
        $response = $this->json('POST', '/api/v1/isBusinessDay/',[
            'initialDate' => "November 10 2018"
        ]);
    
        $response->assertStatus(200);
    
    }


    /**
     * @test POST HTTP Status
     */
    public function test_postBusinessDatesStatus()
    {
        $response = $this->json('POST', '/api/v1/businessDates/', [
            'initialDate' => "November 10 2018",
            'delay' => 3
        ]);

        $response->assertJsonStructure(['ok', 'results'])
                ->assertStatus(200);
    }


    /**
     * @test GET HTTP Status
     */
    public function test_getBusinessDatesStatus()
    {
        $response = $this->json('GET', '/api/v1/businessDates/', [
            'initialDate' => "November 10 2018",
            'delay' => 3
        ]);

        $response->assertJsonStructure(['ok', 'results'])
                 ->assertStatus(200);
    }


    /**
     * @test
     */
    public function test_first_BusinessData()
    {   

        $response = $this->json('POST', '/api/v1/businessDates/', [
            'initialDate' => "November 10 2018",
            'delay' => 3
        ]);

        $response ->assertExactJson([
            "ok" => true,
            "initialQuery" => [
                "initialDate" => "November 10 2018",
                "delay" => 3
            ],
            "results" => [
                "businessDate" => "2018-11-15T00:00:00.000000Z",
                "weekendDays" => 2,
                "holidayDays" => 0,
                "totalDays" => 5
            ]
        ])
        ->assertSuccessful()
        ->assertStatus(200);

    }


    /**
     * Second Usecase
     * @test
     */
    public function test_second_BusinessData()
    {   

        $response = $this->json('POST', '/api/v1/businessDates/', [
            'initialDate' => "November 15 2018",
            'delay' => 3
        ]);

        $response ->assertExactJson([
            "ok" => true,
            "initialQuery" => [
                "initialDate" => "November 15 2018",
                "delay" => 3
            ],
            "results" => [
                "businessDate" => "2018-11-19T00:00:00.000000Z",
                "weekendDays" => 2,
                "holidayDays" => 0,
                "totalDays" => 4
            ]
        ])
        ->assertSuccessful()
        ->assertStatus(200);

    }



    /**
     * Third Edgecase
     * @test
     */
    public function test_third_BusinessData()
    {   

        $response = $this->json('POST', '/api/v1/businessDates/', [
            'initialDate' => "December 25 2018",
            'delay' => 20
        ]);

        $response ->assertExactJson([
            "ok" => true,
            "initialQuery" => [
                "initialDate" => "December 25 2018",
                "delay" => 20
            ],
            "results" => [
                "businessDate" => "2019-01-18T00:00:00.000000Z",
                "weekendDays" => 6,
                "holidayDays" => 0,
                "totalDays" => 24
            ]
        ])
        ->assertSuccessful()
        ->assertStatus(200);

    }





    /**
     * First Usecase `GET`
     * @test
     */
    public function test_first_getBusinessData()
    {   

        $response = $this->json('POST', '/api/v1/businessDates/', [
            'initialDate' => "November 10 2018",
            'delay' => 3
        ]);

        $response ->assertExactJson([
            "ok" => true,
            "initialQuery" => [
                "initialDate" => "November 10 2018",
                "delay" => 3
            ],
            "results" => [
                "businessDate" => "2018-11-15T00:00:00.000000Z",
                "weekendDays" => 2,
                "holidayDays" => 0,
                "totalDays" => 5
            ]
        ])
        ->assertSuccessful()
        ->assertStatus(200);

    }


    /**
     * Second Usecase `GET`
     * @test
     */
    public function test_second_getBusinessData()
    {   

        $response = $this->json('POST', '/api/v1/businessDates/', [
            'initialDate' => "November 15 2018",
            'delay' => 3
        ]);

        $response ->assertExactJson([
            "ok" => true,
            "initialQuery" => [
                "initialDate" => "November 15 2018",
                "delay" => 3
            ],
            "results" => [
                "businessDate" => "2018-11-19T00:00:00.000000Z",
                "weekendDays" => 2,
                "holidayDays" => 0,
                "totalDays" => 4
            ]
        ])
        ->assertSuccessful()
        ->assertStatus(200);

    }



    /**
     * Third Edgecase `GET`
     * @test
     */
    public function test_third_getBusinessData()
    {   

        $response = $this->json('GET', '/api/v1/businessDates/', [
            'initialDate' => "December 25 2018",
            'delay' => 20
        ]);

        $response ->assertExactJson([
            "ok" => true,
            "initialQuery" => [
                "initialDate" => "December 25 2018",
                "delay" => 20
            ],
            "results" => [
                "businessDate" => "2019-01-18T00:00:00.000000Z",
                "weekendDays" => 6,
                "holidayDays" => 0,
                "totalDays" => 24
            ]
        ])
        ->assertSuccessful()
        ->assertStatus(200);

    }

}
