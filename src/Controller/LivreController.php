<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use App\Repository\EmpruntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

/**
 * @Route("/livre")
 */
class LivreController extends AbstractController
{
    /**
     * @Route("/", name="livre_index", methods={"GET"})
     */
    public function index(LivreRepository $lr, EmpruntRepository $er): Response
    {

        return $this->render('livre/index.html.twig', [
            'livres' => $lr->findAll(),
            // 'empruntsNull' => $er->findByNonRendus(),
            'livres_empruntes' => $lr->findByEmpruntes()
        ]);
    }

    /**
     * @Route("/nouveau", name="livre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $nouveauLivre = new Livre();
        $formLivre = $this->createForm(LivreType::class, $nouveauLivre);
        $formLivre->handleRequest($request);

        if ($formLivre->isSubmitted() && $formLivre->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $fichier = $formLivre->get("image")->getData();
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

            $entityManager->persist($nouveauLivre);
            $entityManager->flush();
            $this->addFlash("success", "Le livre <strong>" . $nouveauLivre->getTitre() . "</strong> a bien été ajouté");
            return $this->redirectToRoute('livre_index');
        }

        $form = $formLivre->createView();
        $form->options["titre_formulaire"] = "Ajouter un livre";
        return $this->render('livre/new.html.twig', [
            'livre' => $nouveauLivre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="livre_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="livre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Livre $livre): Response
    {
        $formLivre = $this->createForm(LivreType::class, $livre);
        $formLivre->handleRequest($request);
        if ($formLivre->isSubmitted() && $formLivre->isValid()) {
            $fichier = $formLivre->get("image")->getData();
            if($fichier){
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nomFichier .= uniqid();
                $nomFichier .= "." . $fichier->guessExtension();
                $nomFichier = str_replace(" ", "_", $nomFichier);
                $dossier  = $this->getParameter("dossier_images") . "livres";
                $fichier->move($dossier, $nomFichier);
                $livre->setCouverture($nomFichier);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Le livre n°" . $livre->getId() . " a été modifié");
            return $this->redirectToRoute('livre_index');
        }

        $formLivre = $formLivre->createView();
        $formLivre->options["titre_formulaire"] = "Modifier le livre n°" . $livre->getId();
        return $this->render('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $formLivre//->createView(),
        ]);
    }

    /**
     * @Route("/{id}/supprimer", name="livre_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Livre $livre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livre);
            $entityManager->flush();
        }
        return $this->redirectToRoute('livre_index');
    }

    /**
     * @Route("s/{id}", name="livre_afficher", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function afficher(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

}
