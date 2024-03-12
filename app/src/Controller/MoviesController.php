<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAlias;
use Symfony\Component\Routing\Attribute\Route;

class MoviesController extends AbstractController
{

    private $entityManager;

    function __construct(
        EntityManagerInterface $entityManager,
    )
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/movies', name: 'app_movies', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        //Logic
        //TO DO -> print a list/table with links to each movie
        $movies = $this->entityManager->getRepository(Movie::class)->findBy(["visible" => 1]);

        return $this->render('movies/index.html.twig', [
            'title' => 'Movies List',
            'elements' => \count($movies),
            'data' => $movies
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
