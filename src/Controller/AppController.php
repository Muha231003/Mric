<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\GuardService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class AppController extends AbstractController
{
    public function __construct(private readonly GuardService $guardService)
    {
    }

    /** Route GET - Restituire i template */

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
    public function account(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->guardService->Guard(function () use ($entityManagerInterface, $request) {
            $repository = $entityManagerInterface->getRepository(Game::class);
            $games = $repository->findBy(["mainReferee" => $request->getSession()->get($_ENV["SESSION_COOKIE"])]);

            $repository = $entityManagerInterface->getRepository(GameEvent::class);

            $reds = $repository->findBy(["game" => array_map(function ($game) {
                /** @var Game $game  */
                return $game->getId();
            }, $games), "type" => "red"]);

            $yellows = $repository->findBy(["game" => array_map(function ($game) {
                /** @var Game $game  */
                return $game->getId();
            }, $games), "type" => "yellow"]);

            return $this->render('page/account.html.twig', ["games" => $games, "reds" => $reds, "yellows" => $yellows]);
        }, $request);
    }
}
