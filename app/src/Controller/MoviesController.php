<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAlias;
use Symfony\Component\Routing\Attribute\Route;

class MoviesController extends AbstractController
{

    #[Route('/movies/{name}', name: 'app_movies', defaults: ['name' => null], methods: ['GET', 'HEAD'])]
    public function index($name): Response
    {
        //Logic
        //TO DO -> from database select the movies from a table


        return $this->render('movies/index.html.twig', [
            'title' => '',
            'h1' => $name,

        ]);

    }

    /**
     * oldRouteMethod
     *
     * @return Response
     */
    #[RouteAlias("/old", name: "old")]
    public function oldMethod(): Response
    {
        return $this->json([
            'message' => 'Old Method.',
            'path' => 'src/Controller/MoviesController.php',
        ]);
    }
}
