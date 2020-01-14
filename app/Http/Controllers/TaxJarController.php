<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxJarController extends Controller
{
    public function EPay(){

      $client = \TaxJar\Client::withApiKey('acbd9b5feaaf719f71b07fa8f1fbb48c');

     

      $ip = request()->ip();
      $api_key = 'at_AyedhJ6IcgAzwMWCHBQ515VnjzFM3';
      $api_url = 'https://geo.ipify.org/api/v1';

      $url = "{$api_url}?apiKey={$api_key}&ipAddress={$ip}";
    
      $result = file_get_contents($url);
// dd($result);
      $address = json_decode($result, true);
      $postalCode = $address['location']['postalCode'];
      $country = $address['location']['country'];

      if ($country == 'US'){
         
          
          $tax = $client->ratesForLocation($postalCode, [ 'country' => $country ]);

          $rate = $tax->combined_rate;
      }
      else{

        try {
          $tax = $client->ratesForLocation('', [  'country' => $country ]);

          $rate = $tax->standard_rate;
        }
        catch(Exception $e) {
          dd($e);
        }
      }

      dd($rate);
      
    }








    public function Tax(){

        $client = \TaxJar\Client::withApiKey('acbd9b5feaaf719f71b07fa8f1fbb48c');

        $zip = '11219';

        $rates = $client->ratesForLocation($zip);
        
        
        dd($rates->combined_rate);

        $order_taxes = $client->taxForOrder([
            'from_country' => 'US',
            'from_zip' => '11219',
            'from_state' => 'NY',
            //'to_country' => 'US',
            'to_zip' => '11219',
            'to_state' => 'NY',
            'amount' => 26.50,
            'shipping' => 1.5,
            'line_items' => [
              [
                'quantity' => 1,
                'unit_price' => 15.0,
                'product_tax_code' => 19002
              ],
              [
                'quantity' => 1,
                'unit_price' => 10.0,
                'product_tax_code' => 20010
              ]
            ]
          ]);

          include "C:\Users\Yechiel Tauber\AppData\Roaming\Composer\webSites\myFirstLaravelSite\usaepay.php";    // Change this path to the location you have save usaepay.php

$tran=new \umTransaction;

$tran->key="_dp4fQMWt3rY3H4XD60Os42ybnbV1qP5";      // Your Source Key
$tran->pin="1234";      // Source Key Pin
$tran->usesandbox=true;     // Sandbox true/false
//$tran->ip=$REMOTE_ADDR;   // This allows fraud blocking on the customers ip address
$tran->testmode=0;    // Change this to 0 for the transaction to process

$tran->command="cc:sale";    // Command to run; Possible values are: cc:sale, cc:authonly, cc:capture, cc:credit, cc:postauth, check:sale, 
                             //check:credit, void, void:release, refund, creditvoid and cc:save. Default is cc:sale.

$tran->card="4000100011112224";     // card number, no dashes, no spaces
$tran->exp="0919";          // expiration date 4 digits no /
$tran->amount="1.00";           // charge amount in dollars
$tran->invoice="1234";          // invoice number.  must be unique.
$tran->cardholder="Test T Jones";   // name of card holder
$tran->street="1234 Main Street";   // street address
$tran->zip="05673";         // zip code
$tran->description="Online Order";  // description of charge
$tran->cvv2="123";          // cvv2 code


echo "`<h1>`Please wait one moment while we process your card...`<br>`\n";
flush();

if($tran->Process())
{
    echo "<b>Card Approved</b><br>";
    echo "<b>Authcode:</b> " . $tran->authcode . "<br>";
    //echo "<b>Token:</b> " . $tran->cardnum . "<br>";
    echo "<b>RefNum:</b> " . $tran->refnum . "<br>";
    echo "<b>AVS Result:</b> " . $tran->avs_result . "<br>";
    echo "<b>Cvv2 Result:</b> " . $tran->cvv2_result . "<br>";
} else {
    echo "<b>Card Declined</b> (" . $tran->result . ")<br>";
    echo "<b>Reason:</b> " . $tran->error . "<br>";
    if(@$tran->curlerror) echo "<b>Curl Error:</b> " . $tran->curlerror . "<br>";
}       

        dd($tran);
    }
}
