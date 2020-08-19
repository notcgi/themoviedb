<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Date;

class Controller extends BaseController
{
    /**
     * @param string $id
     * @return array[]|false
     */
    public function getFilm(string $id='popular'){
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.themoviedb.org/3/',
        ]);
        try {
            $response = $client->get(
                request('search',false)?"search/movie":"movie/$id",
                [
                    'query' => [
                        'api_key'=>config('app.tmdb_api'),
                        'query'=>$id
                    ]
                ]
            );
        } catch (ClientException $e) {
            return ['errors'=>['detail'=>$e->getResponse()->getBody(), 'message'=>$e->getMessage()]];
        }

        $response = @json_decode($response->getBody()->getContents());
        if (!$response or !(@$response->results??@$response->title)) abort(400);
        $response=collect(@$response->results ?? [$response])
            ->map(function ($film){
                return [
                    'id'=>$film->id,
                    'title'=>$film->title,
                    'poster_path'=>$film->poster_path,
                    'popularity'=>$film->popularity,
                    'overview'=>$film->overview,
                    'release_date'=> @$film->release_date?(new \DateTime($film->release_date))->format('Y'):null,
                ];
            });
        return $response->toArray();
    }
}
