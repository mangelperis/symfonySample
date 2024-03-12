<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MovieController extends AbstractController
{
    private $entityManager;
    private $translator;

    function __construct(
        EntityManagerInterface $entityManager,
        TranslatorInterface    $translator
    )
    {
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    #[Route('/movie/{id}', name: 'movie_show')]
    public function index(int $id): Response
    {
        /** @var Movie $movie */
        $movie = $this->entityManager->getRepository(Movie::class)->findOneBy(['id' => $id]);

        if (!$movie) {
            throw $this->createNotFoundException(
                \sprintf('No movie found for ID: %s', $id)
            );
        }
        return $this->render('movie/index.html.twig', [
            'title' => \sprintf('%s', $movie->getName()),
            'duration' => \sprintf('%s %s', $movie->getDuration(), $this->translator->trans('movie.duration')),
            'director' => $movie->getDirector(),
            'synopsis' => $movie->getSynopsis(),
            'score' => $movie->getScore()
        ]);

    }
}
