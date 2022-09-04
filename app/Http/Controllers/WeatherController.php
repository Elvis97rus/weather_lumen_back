<?php

namespace App\Http\Controllers;

use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Weather;

class WeatherController extends Controller
{

    public function index(){
        $w = Weather::all();
        return response($w);
    }

    // data = {lat: ..., lon: ..., date: ...}
    public function getWeather(Request $request)
    {
        $apiKey = '94843b6c224e236b862411f12fc60e23';
        if (!isset($request->lat) && !isset($request->lon)){
            $ip = json_decode(Http::get('https://api.ipify.org?format=json'), TRUE)['ip'];
            $ipReq = json_decode(Http::get('http://ip-api.com/json/' . $ip), TRUE);
            $lat = $ipReq['lat'];
            $lon = $ipReq['lon'];
            $date = '25.05.2022';
        } else {
            $lat = $request['lat'];
            $lon = $request['lon'];
            $date = $request['date'];
        }

        $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&units=metric&appid=$apiKey&lang=ru";
        $weather = json_decode(Http::get($url), TRUE);

        Weather::create([
            'city' => $weather['name'],
            'coords' => "$lat,$lon",
            'result' => json_encode($weather),
            'date' => $date,
        ]);

        $res = [
            'city' => $weather['name'],
            'weather_des' => $weather['weather'][0]['description'],
            'weather_temp' => $weather['main']['temp'],
            'weather_wind' => $weather['wind']['speed']
        ];

        return json_encode($res);
    }
}
