<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{

    #[Route('/', name: 'app')]
    public function app(): Response
    {
        $this->addFlash("warning", "Sie haben keine Voranstehenden Spiele.");

        return $this->render('page/index.html.twig');
    }

    #[Route('/done', name: 'app.done')]
    public function done(): Response
    {
        return $this->render('page/done.html.twig');
    }

    #[Route('/create', name: 'app.create')]
    public function create(): Response
    {
        return $this->render('page/create.html.twig');
    }
}
