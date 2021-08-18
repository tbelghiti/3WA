<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dnsimmons\OpenWeather\OpenWeather;

class WeatherController extends Controller
{
    public function index(){


        $weather = new OpenWeather ();
        $current = $weather->getCurrentWeatherByCityName('rabat','metric');
        // dd($current);
        return view('/weather');
    }


    public function search(Request $request) 
    {
        $weather = new OpenWeather();
    $current = $weather->getCurrentWeatherByCityName($request->name , 'metric');
    if($current == false)
    {
        return back()-> with('status','Nom de la ville inexistant !');
    }
        //  dd($current);
        // return back()->with('true','it exist', ["current" => $current]);

        return view('results', ["current" => $current]);
    }


    public function search2(Request $request) 
    {
        $weather = new OpenWeather();
        // $longitude = "-6.8227";
        // $latitude = "34.0242";

        $longitude = $request->long;
        $latitude = $request->lat;

        
    $current = $weather->getCurrentWeatherByCoords($latitude, $longitude, 'metric');
    //dd($current);
    if($current == false)
    {
        return back()-> with('status','Nom de la ville inexistant !');
    }
         // dd($current);
        // return back()->with('true','it exist', ["current" => $current]);

        return view('results', ["current" => $current]);
    }
    
}


 