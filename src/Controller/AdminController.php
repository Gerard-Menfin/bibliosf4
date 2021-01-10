<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository;
use App\Repository\AbonneRepository;
use App\Repository\EmpruntRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/gestion", name="gestion")
     * @IsGranted("ROLE_BIBLIOTHECAIRE")
     */
    public function gestion(LivreRepository $lr, EmpruntRepository $er, AbonneRepository $ar){
        $emprunts = $er->findAll(["date_rendu" => "ASC", "date_sortie" => "ASC"]);
        $empruntsEnCours = $er->findByNonRendus();
        $emprunts["liste"] = $emprunts;
        $emprunts["nb"] = $er->nb();
        $emprunts["en cours"] = $empruntsEnCours;
        
        $livres["liste"] = $lr->findAll();
        $livres["nb"] = $lr->nb();
        $livres["nbSortis"] = $lr->nbSortis();
        $livres["nbDisponibles"] = $lr->nbDisponibles();
        $livres["plusAncienEmprunt"] = count($empruntsEnCours) ? $empruntsEnCours[0] : null;
        $livres_empruntes = $lr->lesPlusEmpruntes();
        // $livres_empruntes = array_splice($livres_empruntes, 0, 5);
        $livres["plusEmprunte"] = $livres_empruntes[0];
        $livres["moinsEmprunte"] = end($livres_empruntes);
        
        $abonnes["liste"] = $ar->findAll();
        $abonnes["nb"] = $ar->nb();
        $abonnes["emprunteurs"] = $ar->findByLivresNonRendus();
        $abs = $ar->findOrderedByNbEmprunts();
        $abonnes["assidu"] = empty($abs) ? null : $abs[0];
        $bibliophiles = $ar->findOrderedByNbLivresEmpruntes();
        $abonnes["bibliophile"] = empty($bibliophiles) ?: $bibliophiles[0];

        $nombdd = $lr->nomBDD();

        return $this->render("admin/index.html.twig", compact("livres", "abonnes", "emprunts", "nombdd"));
    }

}
