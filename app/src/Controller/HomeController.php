<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController
{
    /**
     * @return Response
     */
    #[Route(path: '/', name: 'app_home_index')]
    public function index(): Response
    {
        $model = 'Example';
        $view = new Response(($model));

        return $view;
    }


}