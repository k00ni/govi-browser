<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DataPrivacyController extends AbstractController
{
    #[Route('/data-privacy', name: 'data-privacy')]
    public function handleRequest(): Response
    {
        return $this->render('data_privacy.html.twig');
    }
}