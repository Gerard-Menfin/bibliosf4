<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(LivreRepository $lr)
    {
        //EXO: Récupérer la liste des livres et l'envoyer à la vue

        $liste_livres = $lr->findAll();
        // return $this->render('base.html.twig', [ "liste_livres" => $liste_livres ]);
        return $this->render("base.html.twig", compact("liste_livres"));
    }
}
