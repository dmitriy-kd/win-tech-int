<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route(
        '/test',
        name: 'test',
        methods: ['GET']
    )]
    public function show(): Response
    {
        return new JsonResponse(['test' => 'test']);
    }
}