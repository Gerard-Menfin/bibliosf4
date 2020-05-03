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

    /**
     * @Route("/profil/ajouter-livre", name="livre_ajouter", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function ajouter(LivreRepository $lr, EntityManager $em, Request $rq){
        $nouveauLivre = new Livre;
        $formAjouter = $this->createForm(FormLivreType::class, $nouveauLivre);
        $formAjouter->handleRequest($rq);
        if($formAjouter->isSubmitted()) {
            if($formAjouter->isValid() ) {
                $fichier = $formAjouter->get("couverture")->getData();
                if($fichier){
                    $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                    $nomFichier .= uniqid();
                    $nomFichier .= "." . $fichier->guessExtension();
                    $nomFichier = str_replace(" ", "_", $nomFichier);
                    // on enregistre le fichier téléchargé dans le dossier des images pour les couvertures de livre
                    $dossier  = $this->getParameter("dossier_images") . "livres";
                    $fichier->move($dossier, $nomFichier);
                    $nouveauLivre->setCouverture($nomFichier);
                }

                $em->persist($nouveauLivre);
                $em->flush();
                $this->addFlash("success", "Nouveau livre : <i>" . $nouveauLivre->getTitre() .  "</i> ajouté");
                return $this->redirectToRoute("accueil");
            } else{
                $this->addFlash("danger", "Le formulaire n'est pas valide");
            }
        }

        $formAjouter = $formAjouter->createView();
        return $this->render("livre/form_ajouter.html.twig", compact("formAjouter"));
    }


}
