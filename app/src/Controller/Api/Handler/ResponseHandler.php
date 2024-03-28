<?php
declare(strict_types=1);


namespace App\Controller\Api\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseHandler
{
    /**
     * @param $data
     * @param int $statusCode
     * @return Response
     */
    public function createResponse($data, int $statusCode = Response::HTTP_OK): Response
    {
        return new Response($data, $statusCode);
    }

    public function createSuccessResponse($data, $statusCode = Response::HTTP_OK, $headers = []): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'data' => $data,
        ], $statusCode, $headers);
    }

    public function createErrorResponse($message, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, $headers = []): Response
    {
        return new JsonResponse([
            'success' => false,
            'error' => $message,
        ], $statusCode, $headers);
    }
}