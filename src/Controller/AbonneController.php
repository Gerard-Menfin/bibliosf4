<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Form\AbonneType;
use App\Repository\AbonneRepository;
use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Password;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AbonneController extends AbstractController
{
    /**
     * @Route("/abonne", name="abonne_index", methods={"GET"})
     */
    public function index(AbonneRepository $abonneRepository): Response
    {
        
        return $this->render('abonne/index.html.twig', [
            'abonnes' => $abonneRepository->findAll(),
        ]);
    }

    /**
     * @Route("/abonne/nouveau", name="abonne_new", methods={"GET","POST"})
     */
    public function new(Request $request, Password $passwordEncoder): Response
    {
        $abonne = new Abonne();
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $abonne->setPassword( $passwordEncoder->encodePassword($abonne, $form->get("password")->getData()) );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($abonne);
            $entityManager->flush();

            return $this->redirectToRoute('abonne_index');
        }

        return $this->render('abonne/new.html.twig', [
            'abonne' => $abonne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/abonne/{id}", name="abonne_show", methods={"GET"})
     */
    public function show(Abonne $abonne): Response
    {
        return $this->render('abonne/show.html.twig', [
            'abonne' => $abonne,
        ]);
    }

    /**
     * @Route("/abonne/{id}/modifier", name="abonne_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Abonne $abonne): Response
    {
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('abonne_index');
        }

        return $this->render('abonne/edit.html.twig', [
            'abonne' => $abonne,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/abonne/{id}", name="abonne_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Abonne $abonne): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonne->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($abonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('abonne_index');
    }
    
    /**
     * @Route("/profil", name="profil")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function profil(){
        return $this->render("abonne/profil.html.twig");
    }

    /**
     * @Route("/profil/reserver-livre", name="reserver")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function reserver(EmpruntRepository $er, LivreRepository $lr){
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
            foreach($empruntsLivresNonRendus as $livreNonRendu){
                //On compare le livre actuel avec le livreNonRendu
                if($livre->getId() == $livreNonRendu->getId()){
                    //si l'id du livre est égale à l'id du livre non rendu, alors $nonRendu est vrai
                    $nonRendu = true;
                }
            }

            //Si $nonRendu est faux, on ajoute le livre à l'array des livres rendus
            if(!$nonRendu){
                $livresRendus[] = $livre;
            }
        }

        return $this->render("abonne/reservation.html.twig", compact("livresRendus"));
    }
}
