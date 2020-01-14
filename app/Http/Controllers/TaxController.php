<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class TaxController extends Controller
{
    public function GetTaxForOrder(Order $order){



      $bags = [["type" => "variation",
              "item_type" => null,
              "id" => 6411,
              "media" => "https=>\/\/s3.amazonaws.com\/meilech.dev\/media\/34\/conversions\/discover-thumbnail.jpg",
              "path" => "\/products\/my-product\/39310",
              "meta" => "[\"Size\"=> \"20\", \"Color\"=> \"Blue\"]",
              "price" => 10,
              "title" => "My product",
              "vendor" => null,
              "quantity" => 1,"total"=>10,"taxable"=>true,"tax_amount"=>0.8875],
        ["type" => "variation", "item_type" => null, "id" => 6414,
          "media"=>"https=>\/\/s3.amazonaws.com\/meilech.dev\/media\/34\/conversions\/discover-thumbnail.jpg","path"=>"\/products\/my-product\/39310",
          "meta"=>"[\"Size\"=> \"20\", \"Color\"=> \"Green\"]","price"=>10,"title"=>"My product",
          "vendor"=>null,"quantity"=>1,"total"=>10,"taxable"=>true,"tax_amount"=>0.8875],
          ["type"=>"variation","item_type"=>"phsical","id"=>6419,
            "media"=>"https=>\/\/s3.amazonaws.com\/meilech.dev\/media\/50\/conversions\/next-level-dance-web_2-large-thumbnail.jpg",
            "path"=>"\/products\/digital\/39312","meta"=>"[\"Size\"=> \"1\"]","price"=>23,
            "title"=>"Digital","vendor"=>"adipisci","quantity"=>1,"total"=>23,"taxable"=>true,
            "tax_amount"=>2.04125]];

            //return $bags;

       //$address = file_get_contents('http://ZiptasticAPI.com/11219');
       //return $address;


        $client = \TaxJar\Client::withApiKey("acbd9b5feaaf719f71b07fa8f1fbb48c");

        $refund = $client->createRefund([
          'transaction_id' => '322',
          'transaction_date' => '2015/05/14',
          'transaction_reference_id' => '127',
          'to_country' => 'US',
          'to_zip' => '90002',
          'to_state' => 'CA',
          'to_city' => 'Los Angeles',
          'to_street' => '123 Palm Grove Ln',
          'amount' => -16.5,
          'shipping' => -1.5,
          'sales_tax' => -0.95,
          'line_items' => [
            [
              'quantity' => 1,
              'product_identifier' => '12-34243-9',
              'description' => 'Fuzzy Widget',
              'unit_price' => -15,
              'sales_tax' => -0.95
            ]
          ]
        ]);

        return(json_encode($refund));

        $from = '2018/01/01';
        $to = '2020/01/01';
        $order_ids = $client->listOrders([
          'from_transaction_date' => $from,
          'to_transaction_date' => $to
        ]);

        $orders = collect($order_ids)->map(function($o)
        {
          $client = \TaxJar\Client::withApiKey("acbd9b5feaaf719f71b07fa8f1fbb48c");
          return $client->showOrder($o);
        });

        //return $orders;

        // $order = $client->createOrder([
        //   'transaction_id' => '398',
        //   'transaction_date' => '2019/09/9',
        //   'to_country' => 'US',
        //   'to_zip' => '90002',
        //   'to_state' => 'CA',
        //   'to_city' => 'Los Angeles',
        //   'to_street' => '123 Palm Grove Ln',
        //   'amount' => 16.5,
        //   'shipping' => 1.5,
        //   'sales_tax' => 0.95,
        //   'line_items' => [
        //     [
        //       'quantity' => 1,
        //       'product_identifier' => '12-34243-9',
        //       'description' => 'Fuzzy Widget',
        //       'unit_price' => 15,
        //       'sales_tax' => 0.95
        //     ]
        //   ]
        //  ]);

       //  dd ($order);


        $categories = $client->categories();
        $collection = collect($categories)->map(function($c) {
          return [
            'label' => $c 
          ];
        });
       // return $categories;


        $order = [
            'from_country' => 'US',
            'from_zip' => '92093',
            'from_state' => 'CA',
            'from_city' => 'La Jolla',
            'from_street' => '9500 Gilman Drive',
            'to_country' => 'US',                       //required
            'to_zip' => '90002',
            'to_state' => 'CA',
            'to_city' => 'Los Angeles',
            'to_street' => '1335 E 103rd St',
            'amount' => 45,
            'shipping' => 0,                          //required
            'nexus_addresses' => [
              [
                'id' => 'Main Location',
                'country' => 'US',
                'zip' => '92093',
                'state' => 'CA',
                'city' => 'La Jolla',
                'street' => '9500 Gilman Drive',
              ]
            ],
            'line_items' => [
              [
                'id' => '1',
                'quantity' => 1,
                'product_tax_code' => '20010',
                'unit_price' => 15,
                'discount' => 0
              ],
              [
                'id' => '2',
                'quantity' => 1,
                'product_tax_code' => '20010',
                'unit_price' => 15,
                'discount' => 0
              ],
              [
                'id' => '3',
                'quantity' => 1,
                'product_tax_code' => '20010',
                'unit_price' => 15,
                'discount' => 0
              ]
            ]
          ];
            $taxes = $client->taxForOrder($order);
          
            $taxesJson = json_encode($taxes);
            return ($taxesJson);
    }
}
