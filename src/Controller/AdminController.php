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
     * @IsGranted("ROLE_ADMIN")
     */
    public function gestion(LivreRepository $lr, EmpruntRepository $er, AbonneRepository $ar){
        $nb_abonnes = $ar->nb();
        $nb_livres = $lr->nb();
        $nb_emprunts = $er->nb();
        // $livres_empruntes = array_splice($livres_empruntes, 0, 5);

        return $this->render("admin/index.html.twig", compact("nb_abonnes", "nb_livres", "nb_emprunts"));
    }

}
