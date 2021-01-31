<?php

namespace App\Transform;

use Symfony\Component\Form\DataTransformerInterface;
use App\Library\SwapiPlugin;
use App\Transform\FilmTransformer;

class DetailTransformer implements DataTransformerInterface
{

    private $api;
    private $filmTransform;

    public function __construct(
        SwapiPlugin $api,
        FilmTransformer $filmTransform
    ) {
        $this->api = $api;
        $this->filmTransform = $filmTransform;
    }

    public function transform($person)
    {
        $newArray = [];
        $personTransformer = [
            'name' => $person['name'],
            'height' => $person['height'],
            'weight' => $person['mass'],
            'url' => $person['url']

        ];


        $arraMovies = [];

        foreach ($person['films'] as $movie) {
            $searchMovie = $this->api->consult($movie);
            $body = json_decode($searchMovie->getBody()->getContents(), true);
            $film = $this->filmTransform->transform($body);
            array_push($arraMovies, $film);
        }

        $personTransformer['movies'] = $arraMovies;
        array_push($newArray, $personTransformer);
        return [$personTransformer];
    }


    public function reverseTransform($issueNumber)
    {
    }
}
