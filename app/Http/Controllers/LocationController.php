<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getZipCode(Request $request)
    {
        if (session()->get('zip_code')) {
            return session()->get('zip_code');
        }
        $ip = $request->ip();
        $api_key = env('ZIP_API');
        $api_url = 'https://geo.ipify.org/api/v1';
        //
        $ip = '75.99.51.218';
        $results = file_get_contents("{$api_url}?apiKey={$api_key}&ipAddress={$ip}");
        $address = json_decode($result, true);
        $zip = $address['location']['postalCode'];
        $country = $address['location']['country'];
 
        if ($country == 'US') {
            session()->put('zip_code', [
                'zip' => $zip,
                'country' => $country,
                'tax_rate' => $this->getTaxRateByZip($zip),
            ]);
        }
        else {
            session()->put('zip_code', [
                'country' => $country,
                'tax_rate' => $this->getTaxRateByCountry($country),
            ]);
        }
     
        return response()
            ->json(session()->get('zip_code'));
    }

    public function SetLocationAndGetTaxRate(Request $request) {

        if ($request->zipCode){
            return $this->setZipCode($request->zipCode);
        }
        elseif ($request->country) {
            return $this->setCountry($request->country);
        }
        else {
            return 0;
        }
    }

    public function GetLocationByZip(Request $request){

        $zip = $request->zipCode;
        return file_get_contents('http://ZiptasticAPI.com/{$zip}');
    }


    private function setZipCode($zipCode) {
        session()->put('zip_code', [
            'zip' => $zipCode,
            'country' => 'US',
            'tax_rate' => $this->getTaxRateByZip($zipCode),
        ]);
        return session()->get('zip_code');
    }


    private function setCountry($country) {
        session()->put('zip_code', [
            'country' => $country,
            'tax_rate' => $this->getTaxRateByCountry($country),
        ]);
        return session()->get('zip_code');
    }


    private function getTaxRateByZip($zip) {
        $data = \TaxJar\Client::withApiKey(env('TAX_JAR'))
            ->ratesForLocation($zip);
        return $data->combined_rate;
    }


    private function getTaxRateByCountry($country) {
        try {
            $data = \TaxJar\Client::withApiKey(env('TAX_JAR'))
            ->ratesForLocation('', ['country' => $country]);
            return $data->standard_rate;
        } catch (\Exception $e) {
            return 0;
        }
    }
}