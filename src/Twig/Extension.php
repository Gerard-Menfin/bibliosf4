<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    // pour créer un nouveau filtre
    public function getFilters()
    {
        return [
            new TwigFilter('img', [$this, 'baliseImg']),
        ];
    }

    //pour créer une nouvelle fonction
    public function getFunctions()
    {
        return [
            new TwigFunction('balise_image', [$this, 'baliseImg']),
        ];
    }

    public function baliseImg($nomImage, $dossier = "", $classes = "") : string
    {
        $balise = "";
        if($nomImage){
            if($dossier && substr($dossier, -1) != "/"){
                $dossier .= "/";
            }
            $src =  $this->parameters->get("url_images") . $dossier .  $nomImage;
            $balise = "<img src='$src' class='$classes'>";
            $balise = html_entity_decode($balise);
        }
        return $balise;
    }

}
