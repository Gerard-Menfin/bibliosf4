<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Form\FormLivreType;
use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(){
        return $this->render("profil/index.html.twig");
    }

    /**
     * @Route("/profil/reserver-livre", name="reserver")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function reserver(EmpruntRepository $er, LivreRepository $lr, Request $rq, EntityManager $em){
        $livres = $lr->findAll();
        $empruntsLivresNonRendus = $er->findByNonRendus();
        $livresRendus = [];

        // On va parcourir la liste de tous les livres. Pour chaque livre, on va 
        // chercher à savoir s'il fait partie des livres non rendus.
        // S'il ne fait pas partie des livres non rendus, je l'ajoute à l'array $livreRendu
        
        // On parcourt tous les livres de la bibliothèque
        foreach($livres as $livre){
            // je crée une variable booléene qui sera vraie si le livre actuel est non rendus
            $nonRendu = false;

            //Pour chaque livre, on parcourt les livres non rendus
            foreach($empruntsLivresNonRendus as $emprunt){
                //On compare le livre actuel avec le livreNonRendu (en passant par $emprunt->getLivre())
                if($livre->getId() == $emprunt->getLivre()->getId()){
                    //si l'id du livre est égale à l'id du livre non rendu, alors $nonRendu est vrai
                    $nonRendu = true;
                }
            }

            //Si $nonRendu est faux, on ajoute le livre à l'array des livres rendus
            if(!$nonRendu){
                $livresRendus[] = $livre;
            }
        }

        // isMethod permet de savoir si ma requête HTTP est en GET ou POST
        if($rq->isMethod("POST")){
            // La propriété request de l'objet $rq permet de récupérer ce qu'il y a dans $_POST
            $livres = $rq->request->get("livres");
            if($livres){
                foreach ($livres as $id_livre) {
                    $emprunt = new Emprunt;
                    //$this->getUser() : récupère l'utilisateur connecté
                    $emprunt->setAbonne($this->getUser());
                    
                    $emprunt->setLivre($lr->find($id_livre));
                    $emprunt->setDateSortie(new \DateTime("now"));
                    $em->persist($emprunt);
                }
                $em->flush();
                return $this->redirectToRoute("profil");
            }
        }
        return $this->render("abonne/reservation.html.twig", compact("livresRendus"));
    }



}
