<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\GuardService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\User;

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

    #[Route('/game/{id}', name: 'app.game')]
    public function events(Request $request, EntityManagerInterface $entityManagerInterface, int $id): Response|RedirectResponse
    {
        return $this->guardService->Guard(function () use ($entityManagerInterface, $id) {
            $repository = $entityManagerInterface->getRepository(Game::class);
            $game = $repository->findOneBy(["id" => $id]);

            if ($game) {
                return $this->render('page/game.html.twig', ["game" => $game]);
            } else {
                $this->addFlash("warning", "Partia non trovata...");

                return $this->redirect($this->generateUrl("app"));
            }
            
        }, $request);
    }

    /** Route POST - Submit dei form */

    #[Route('/create-submit', name: 'app.create-submit', methods: ["POST"])]
    public function createSubmit(Request $request, EntityManagerInterface $entityManagerInterface): RedirectResponse
    {
        return $this->guardService->Guard(function () use ($request, $entityManagerInterface) {
            $payload = $request->getPayload();
            $luogo = $payload->get("luogo");
            $squadradicasa = $payload->get("squadradicasa");
            $squadraospite = $payload->get("squadraospite");
            $arbitro = $payload->get("arbitro");
            $dataeora = $payload->get("dataeora");

            $repository = $entityManagerInterface->getRepository(User::class);
            $user = $repository->findOneBy(["id" => $request->getSession()->get("session")]);

            if ($luogo && $squadradicasa && $squadraospite && $arbitro && $dataeora && $user) {
                $game = new Game();
                $game->setMainReferee($user);
                $game->setLocation($luogo);
                $game->setLocalTeam($squadradicasa);
                $game->setGuestTeam($squadraospite);
                $game->setSubReferee($arbitro);
                $game->setDate(new DateTime($dataeora));

                $entityManagerInterface->persist($game);
                $entityManagerInterface->flush();

                $this->addFlash("success", "Partia create con successo!");

                return $this->redirect($this->generateUrl("app"));
            }

            $this->addFlash("danger", "Uno o piu campi che sono stati inseriti sono invalidi!");

            return $this->redirect($this->generateUrl("app.create"));
        }, $request);
    }
}
