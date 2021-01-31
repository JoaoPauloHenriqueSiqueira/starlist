<?php

namespace App\Transform;

use Symfony\Component\Form\DataTransformerInterface;

class PersonTransformer implements DataTransformerInterface
{

    public function transform($list)
    {
        $newArray = [];
        foreach ($list as $person) {
            $personTransformer = ['name' => $person['name'], 'url' => $person['url']];
            array_push($newArray, $personTransformer);
        }
        return $newArray;
    }


    public function reverseTransform($issueNumber)
    {
    }
}
