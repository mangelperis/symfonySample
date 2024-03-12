<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MovieController extends AbstractController
{
    #[Route('/movie/{id}', name: 'movie_show')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Movie $movie */
        $movie = $entityManager->getRepository(Movie::class)->findOneBy(['id' => $id]);

        if (!$movie) {
            throw $this->createNotFoundException(
                \sprintf('No movie found for ID: %s', $id)
            );
        }
        return new Response(\sprintf("The movie [%s] duration is %s mins!", $movie->getName(), $movie->getDuration()));
    }
}
