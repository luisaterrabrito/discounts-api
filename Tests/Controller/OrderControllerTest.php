<?php

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class OrderControllerTest extends TestCase
{
    /**
     * Test request getDiscount
     */
    public function testGetDiscount()
    {
       $client = new Client(['base_uri' => 'http://127.0.0.1:8000',
           'headers' => [
               'Accept' => 'application/json',
               'Content-type' => 'application/json'
           ]
       ]);
       $data = [
            "id" => "1",
            "customer-id" => "1",
            "items"=> [
                [
                     "product-id" => "B102",
                     "quantity" => "10",
                     "unit-price" => "4.99",
                     "total" => "49.90"
                ]
            ],
            "total" => "49.90"
       ];
       $request = $client->post('/discount', ['json' => $data]);
       $this->assertEquals(200, $request->getStatusCode());
    }
}
