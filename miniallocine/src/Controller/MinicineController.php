<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Film;

class MinicineController extends AbstractController
{
    /**
     * @Route("/minicine", name="films_index ")
     */
    public function index(): Response
    {
        
        //$films = $this->getDoctrine()->getRepository('App:Film')->findAll();
        
        
        $htmlpage = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Film list!</title>
    </head>
    <body>
        <h1>tFilm list</h1>
        <p>Here are all your film:</p>
        <ul>';
        
        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository(Film::class)->findAll();
        foreach($films as $film) {
            $htmlpage .= '<li>
            <a href="/film/'.$film->getid().'">'.$film->getTitle().'</a></li>';
        }
        $htmlpage .= '</ul>';
        
        $htmlpage .= '</body></html>';
        
        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
            );
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MinicineController.php',
        ]);
    }
    /**
     * Show a film
     *
     * @Route("/{title}/{year}", name="film_show", requirements={"year"="\d+"})
     *    note that the year must be an integer, above
     *
     * @param String $title
     * @param Integer $year
     */
    public function show($title, $year)
    {  
        $filmRepo = $this->getDoctrine()->getRepository('App:Film');
        $film = $filmRepo->findOneBy(['title' => $title, 'year' => $year]);
        $jana= 10;
        if (!$film) {
            throw $this->createNotFoundException('The film does not exist');
        }
        
        $res = '...';
        //...
        
        $res .= '<p/><a href="' . $this->generateUrl('films_index') . '">Back</a>';
        
        return new Response('<html><body>'. $res . '</body></html>');
    }
    
}
