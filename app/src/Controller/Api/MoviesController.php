<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\Api\Handler\ResponseHandler;
use App\Entity\Movie;
use App\Form\Type\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;

class MoviesController extends AbstractFOSRestController
{
    private MovieRepository $movieRepository;
    private SerializerInterface $serializer;
    private ResponseHandler $responseHandler;
    private EntityManagerInterface $entityManager;

    public function __construct(
        MovieRepository        $movieRepository,
        SerializerInterface    $serializer,
        ResponseHandler        $responseHandler,
        EntityManagerInterface $entityManager,
    )
    {
        $this->movieRepository = $movieRepository;
        $this->serializer = $serializer;
        $this->responseHandler = $responseHandler;
        $this->entityManager = $entityManager;
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
        // Check if request content is empty '{}'
        if (empty($request->getContent())) {
            return $this->responseHandler->createErrorResponse('Empty request content', Response::HTTP_BAD_REQUEST);
        }

        // Decode JSON data into an associative array
        $data = json_decode($request->getContent(), true);

        // Check if required fields are present in the data (validator will do this)
//        $requiredFields = ['name', 'duration', 'director', 'synopsis', 'score', 'visible'];
//        foreach ($requiredFields as $field) {
//            if (!array_key_exists($field, $data)) {
//                return $this->responseHandler->createErrorResponse(sprintf('Missing required field: %s', $field), Response::HTTP_BAD_REQUEST);
//            }
//        }

//        Esto va bien a medias...pero NO te cubre los typos admitidos
//        $movie = $this->serializer->deserialize($request->getContent(), Movie::class, 'json');
//        $errors = $this->validator->validate($data);/
//

        //Check duplicates
        $movie = $this->movieRepository->findOneBy(['name' => $data['name']]);
        if ($movie !== null) {
            return $this->responseHandler->createErrorResponse(sprintf('Movie with name [%s] already exists', $data['name']), Response::HTTP_CONFLICT);
        }

        //FormBuilder validator required for the input data...
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);
        //$form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie->setName($data['name']);
            //Cast to allow otherwise strict
            $movie->setDuration((int)$data['duration']);
            $movie->setDirector($data['director']);
            $movie->setSynopsis($data['synopsis']);
            //Cast to allow otherwise strict
            $movie->setScore((int)$data['score']);
            //Default value for the class
            $movie->setVisible($data['visible'] ?? $movie->getVisible());

            // Persist the movie entity
            $this->entityManager->persist($movie);
            $this->entityManager->flush();

            return $this->responseHandler->createResponse(null, 201);
        }
        //Error invalid json
        return $this->responseHandler->createErrorResponse($this->getErrorsFromForm($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors(true, true) as $error) {
            if ($error->getCause() instanceof ConstraintViolation) {
                $errors[] = sprintf("%s : %s -%s-", $error->getOrigin()->getName(), $error->getMessage(), $error->getOrigin()->getData());
            } else {
                $errors[] = $error->getMessage();
            }
        }

        return $errors;
    }
}