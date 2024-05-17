<?php

namespace App\Trait;

use GuzzleHttp\Client;
use Spatie\Geocoder\Geocoder;
use stdClass;

trait Geocodable
{
    public function getGeocodeFromAddress($address)
    {
        $client = new Client();
        $geocoder = new Geocoder($client);
        $geocoder->setApiKey(config('geocoder.key'));
        $result = $geocoder->getCoordinatesForAddress($address);
        
        $geo_data = new stdClass;
        $geo_data->lat = $result['lat'];
        $geo_data->lng = $result['lng'];
        $geo_data->address = '';
        $geo_data->suburb = '';
        $geo_data->state = '';

        if ($result['formatted_address'] != 'result_not_found') {
            if ($result['address_components'][0]->types[0] == 'subpremise') {
                $geo_data->address = $result['address_components'][0]->short_name . '/' . $result['address_components'][1]->short_name . ' ' . $result['address_components'][2]->short_name;
                $geo_data->suburb = $result['address_components'][3]->long_name;
                $geo_data->state = $result['address_components'][5]->short_name;
            } else {
                $geo_data->address = $result['address_components'][0]->short_name . ' ' . $result['address_components'][1]->short_name;
                $geo_data->suburb = $result['address_components'][2]->long_name;
                $geo_data->state = $result['address_components'][4]->short_name;
            }
            
        }
        return $geo_data;
    }
}
