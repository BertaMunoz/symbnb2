<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/hello/{prenom}/age/{age}", name="hello")
     * @Route("/hello", name="hello_base")
     * @Route("/hello/{prenom}", name="hello_prenom")
     */    
    public function hello($prenom = "anonyme", $age = 0){
        return new Response("Bonjour ". $prenom . " vous avez " . $age . " ans"); 
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        $prenoms = ["Lior" => 31, "Raoul" => 25, "Marcel" => 14];

        return $this->render('home/index.html.twig', [ 
            'controller_name' => 'HomeController', 
            'title' => 'Bonjour à tous',
            'age' => 12,
            'tableau' => $prenoms
        ]);
        //return new Response("<h1>Bonjour à tous!</h1>"); 
    }
}
