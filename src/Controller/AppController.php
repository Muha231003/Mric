<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\GuardService;

class AppController extends AbstractController
{

    public function __construct(private readonly GuardService $guardService)
    {
    }

    #[Route('/', name: 'app')]
    public function app(Request $request): Response
    {
        return $this->guardService->Guard(function () {
            return $this->render('page/index.html.twig');
        }, $request);
    }

    #[Route('/done', name: 'app.done')]
    public function done(Request $request): Response
    {
        return $this->guardService->Guard(function () {
            return $this->render('page/done.html.twig');
        }, $request);
    }

    #[Route('/account', name: 'app.account')]
    public function account(Request $request): Response
    {
        return $this->guardService->Guard(function () {
            return $this->render('page/account.html.twig');
        }, $request);
    }

    #[Route('/create', name: 'app.create')]
    public function create(Request $request): Response
    {
        return $this->guardService->Guard(function () {
            return $this->render('page/create.html.twig');
        }, $request);
    }

    #[Route('/events', name: 'app.events')]
    public function events(Request $request): Response
    {
        return $this->guardService->Guard(function () {
            return $this->render('page/events.html.twig');
        }, $request);
    }
}
