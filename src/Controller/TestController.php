<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/test")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/", name="test")
     */
    public function index()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/affichage-responsive")
     */
    public function affichage(){
        return $this->render("test/device.html.twig");
    }


    /**
     * @Route("/test/formulaire", name="test_formulaire")
     */
    public function formulaire(Request $rq): Response
    {
        /**
         * Injection de dépendance : je peux appeler un objet d'une classe que je ne peux pas
         * instancier dans les paramètres d'une méthode
         * ex: Un objet de la classe Request va être instancier quand la méthoder Test::formulaire va
         *     être exécutée. Je pourrais utiliser cet objet dans la méthode
         * 
         * la classe Request : va nous permettre de récupérer des informations concernant la 
         * requête HTTP actuelle (par exemple ce qui va être mis dans $_GET et $_POST)
         * Cet objet Request permet d'accéder aussi à toutes les autres superglobales ($_FILES, $_COOKIE, $_SERVER, ...)
         */
        
        /*  Dans l'objet de la classe Request, la propriété query est un
            objet qui contient la même chose que $_GET
        */
        // dump($rq);
        $titre = ""; $auteur = "";
        if( $rq->query->has("titre") && $rq->query->has("auteur")){
            $titre = $rq->query->get("titre");
            $auteur = $rq->query->get("auteur");
            // dump($titre, $auteur);
            return $this->render('test/formulaire.html.twig', compact("titre", "auteur"));
        }

        return $this->render('test/formulaire.html.twig');
    }

    /**
     * @Route("test/post", name="test_post")
     */
    public function test(Request $rq){
        /*  Dans l'objet de la classe Request, la propriété request est un
            objet qui contient la même chose que $_POST
        */
        if($rq->isMethod("POST")){
            $titre = $rq->request->get("titre");
            $auteur = $rq->request->get("auteur");
            return $this->render('test/formulaire.html.twig', compact("titre", "auteur"));
        }
        return $this->render("test/formulaire.html.twig");
    }

}
