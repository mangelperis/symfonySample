<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\Api\Handler\ResponseHandler;
use App\Repository\MovieRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MoviesController extends AbstractFOSRestController
{
    private MovieRepository $movieRepository;
    private SerializerInterface $serializer;
    private ResponseHandler $responseHandler;

    public function __construct(
        MovieRepository     $movieRepository,
        SerializerInterface $serializer,
        ResponseHandler     $responseHandler
    )
    {
        $this->movieRepository = $movieRepository;
        $this->serializer = $serializer;
        $this->responseHandler = $responseHandler;
    }

    #[Route(path: '/movies', name: 'api_movies_get')]
    public function getMovies(Request $request): Response
    {
        $movies = $this->movieRepository->findAll();

        if ($movies === null) {
            return $this->responseHandler->createErrorResponse("No records fonund");
        }

        $serializedData = $this->serializer->serialize($movies, 'json', ['groups' => 'movie']);
        return $this->responseHandler->createResponse($serializedData);
    }
}