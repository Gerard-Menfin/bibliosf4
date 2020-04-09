<?php

namespace App\Controller;

use App\Repository\EmpruntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(LivreRepository $lr, EmpruntRepository $er)
    {
        //EXO: Récupérer la liste des livres et l'envoyer à la vue
        $liste_livres = $lr->findAll();
        
        // compact("liste_livres", "liste_chloe")
        // équivalent à 
        // [ "liste_livre" => $liste_livres, "liste_chloe" => $liste_livres ]
        return $this->render("base.html.twig", compact("liste_livres"));
    }

    /**
     * @Route("/exo", name="exo")
     */
    public function exo(EmpruntRepository $er, Request $rq)
    {
        $liste_chloe = $er->findChloe();
        // 1. Créez une nouvelle méthode dans le EmpruntRepository pour récupérer
        // tous les livres de l'abonné dont le nom a été tapé dans le formulaire
        // 2. Récupérez le prénom envoyé par le formulaire qui est en méthode get
        $prenom = $rq->query->get("prenom");

        // 3. comme pour Chloé, affichez le résultat dans la vue accueil/index.html.twig
        if($prenom){
            $liste_livres_empruntes_par = $er->findLivresEmpruntesPar($prenom); 
        } else 
            $liste_livres_empruntes_par = [];


        // exo : Afficher la liste des abonnés ayant déjà emprunté un livre d’Alphonse DAUDET
        //       1. créez une méthode qui récupère les emprunts qui concerne l'auteur qui sera donné en paramètres
        //
        $emprunts_livres_alphonse_daudet = $er->findEmpruntsParAuteur("Alphonse Daudet");  
        return $this->render("accueil/index.html.twig", compact( "liste_chloe", "prenom", 
                                                                 "liste_livres_empruntes_par", 
                                                                 "emprunts_livres_alphonse_daudet"
                                                                ));
    }

    /**
     * @Route("/recherche", name="recherche")
     */
    public function recherche(LivreRepository $lr, Request $rq){
        // pour récupérer l'input' "mot_recherche" en GET
        $mot = $rq->query->get("mot_recherche");

        // pour récupérer l'input "mot_recherche" en POST
        // $mot = $rq->request->get("mot_recherche");

        $liste_livres = $lr->findBySearch($mot);
        return $this->render("base.html.twig", compact("liste_livres"));

    }


}
