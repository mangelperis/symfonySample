<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\Api\Handler\ResponseHandler;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MoviesController extends AbstractFOSRestController
{
    private MovieRepository $movieRepository;
    private SerializerInterface $serializer;
    private ResponseHandler $responseHandler;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(
        MovieRepository        $movieRepository,
        SerializerInterface    $serializer,
        ResponseHandler        $responseHandler,
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator,
    )
    {
        $this->movieRepository = $movieRepository;
        $this->serializer = $serializer;
        $this->responseHandler = $responseHandler;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route(path: '/movies', name: 'api_movies_get', methods: ['GET'])]
    public function getMovies(Request $request): Response
    {
        $movies = $this->movieRepository->findAll();

        if ($movies === null) {
            return $this->responseHandler->createErrorResponse("No records fonund");
        }

        $serializedData = $this->serializer->serialize($movies, 'json', ['groups' => 'movie']);
        return $this->responseHandler->createResponse($serializedData);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route(path: '/movie', name: 'api_movie_add', methods: ['POST'])]
    public function addMovie(Request $request): Response
    {
        // Check if request content is empty
        if (empty($request->getContent())) {
            return $this->responseHandler->createErrorResponse('Empty request content', Response::HTTP_BAD_REQUEST);
        }

        // Decode JSON data into an associative array
        $data = json_decode($request->getContent(), true);

        // Check if required fields are present in the data
        $requiredFields = ['name', 'duration', 'director', 'synopsis', 'score', 'visible'];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $data)) {
                return $this->responseHandler->createErrorResponse(sprintf('Missing required field: %s', $field), Response::HTTP_BAD_REQUEST);
            }
        }
        $movie = $this->movieRepository->findOneBy(['name' => $data['name']]);
        if ($movie !== null) {
            return $this->responseHandler->createErrorResponse(sprintf('Movie with name [%s] already exists', $data['name']), Response::HTTP_CONFLICT);
        }

        //JsonSchema or FormBuilder validator required for the input data...
        $movie = new Movie();
        $movie->setName($data['name']);
        $movie->setDuration($data['duration']);
        $movie->setDirector($data['director']);
        $movie->setSynopsis($data['synopsis']);
        $movie->setScore($data['score']);
        $movie->setVisible($data['visible']);

        // Validate the Movie entity (the content)
        $errors = $this->validator->validate($movie);
        if (count($errors) > 0) {
            // Handle validation errors
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = sprintf("%s : %s", $error->getPropertyPath(), $error->getMessage());
            }

            return new Response(json_encode(['errors' => $errorMessages]), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        // Persist the movie entity
        $this->entityManager->persist($movie);
        $this->entityManager->flush();

        return $this->responseHandler->createResponse(null, 201);
    }
}