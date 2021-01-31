<?php

namespace App\Transform;

use Symfony\Component\Form\DataTransformerInterface;

class FilmTransformer implements DataTransformerInterface
{

    public function transform($film)
    {
        $filmTransform = ['name' => $film['title']];
        return $filmTransform;
    }


    public function reverseTransform($issueNumber)
    {
    }
}
