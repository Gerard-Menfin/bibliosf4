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

    /**
     * Pour créer un nouveau filtre
     * 
     */
    public function getFilters()
    {
        return [
            new TwigFilter('img', [$this, 'baliseImg']),
            new TwigFilter('accreditation', [$this, 'accreditation'])
        ];
    }

    /**
     * Pour créer une nouvelle fonction
     * 
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('balise_image', [$this, 'baliseImg']),
        ];
    }


    /**
     * Renvoie une balise <img>
     */
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

    /**
     * Remplace le ROLE_ par un texte défini
     */
    public function accreditation(array $roles) : string {
        // si l'array est vide, $role= "ROLE_USER"
        $role = $roles[0] ?? "ROLE_USER";
        $accreditations = [
            "ROLE_USER" => "Lecteur",
            "ROLE_BIBLIOTHECAIRE" => "Bibliothécaire",
            "ROLE_ADMIN" => "Directeur",
            "ROLE_DEV" => "Développeur",
        ];
        return $accreditations[$role];
    }

}
