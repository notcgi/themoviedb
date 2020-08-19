<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Date;

class Controller extends BaseController
{
    public function getMain(){
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.themoviedb.org/3/',
        ]);
        try {
            $response = $client->get(
                'movie/popular',
                [
                    'query' => ['api_key'=>config('app.tmdb_api')]
                ]
            );
        } catch (ClientException $e) {
            return ['errors'=>['detail'=>$e->getResponse()->getBody(), 'message'=>$e->getMessage()]];
        }
        $response = @json_decode($response->getBody()->getContents());
        if (!$response or !$response->results) return false;
        $response=collect($response->results)
            ->map(function ($film){
                return [
                    'id'=>$film->id,
                    'title'=>$film->title,
                    'poster_path'=>'https://image.tmdb.org/t/p/w500'.$film->poster_path,
                    'release_date'=> (new \DateTime($film->release_date))->format('Y'),
                ];
            });
        dd($response);

        return view('welcome');
    }
}
