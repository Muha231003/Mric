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
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\User;

class GameController extends AbstractController
{
    public function __construct(private readonly GuardService $guardService)
    {
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
                /** @var Game $game */
                $localScores = 0;
                $guestScores = 0;

                $repository = $entityManagerInterface->getRepository(GameEvent::class);
                $events = $repository->findBy(["game" => $id]);

                foreach ($events as $event) {
                    /** @var GameEvent $event */

                    switch ($event->getType()) {
                        case "score":
                            $event->getValue()["team"] === "local" ?  $localScores++ : $guestScores++;
                            break;
                        default:
                    }
                }


                return $this->render('page/game.html.twig', ["game" => $game, "localScores" => $localScores, "guestScores" => $guestScores]);
            } else {
                $this->addFlash("warning", "Partita non trovata...");

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

                $this->addFlash("success", "Partita create con successo!");

                return $this->redirect($this->generateUrl("app"));
            }

            $this->addFlash("danger", "Uno o piu campi che sono stati inseriti sono invalidi!");

            return $this->redirect($this->generateUrl("app.create"));
        }, $request);
    }

    #[Route('/game/{id}/{type}', name: 'app.game.event', methods: ["POST"])]
    public function score(Request $request, EntityManagerInterface $entityManagerInterface, int $id, string $type): RedirectResponse
    {
        return $this->guardService->Guard(function () use ($entityManagerInterface, $id, $request, $type) {
            $repository = $entityManagerInterface->getRepository(Game::class);
            $game = $repository->findOneBy(["id" => $id]);

            if ($game) {
                /** @var Game $game */
                $event = new GameEvent();
                $event->setGame($game);
                $event->setType($type);

                switch ($type) {
                    case "score":
                        $event->setValue(["team" => $request->getPayload()->get("team")]);
                        break;
                    case "yellow":
                        $event->setValue(["player" => (int)$request->getPayload()->get("player")]);
                        break;
                    case "red":
                        $event->setValue(["player" => (int)$request->getPayload()->get("player")]);
                        break;
                    case "time":
                        $event->setValue(["minutes" => (int)$request->getPayload()->get("time")]);
                        break;
                    default:
                        $this->addFlash("danger", "Game event '$type' non trovato...");
                        return $this->redirect($this->generateUrl("app.game", ["id" => $id]));
                }


                $entityManagerInterface->persist($event);
                $entityManagerInterface->flush();

                return $this->redirect($this->generateUrl("app.game", ["id" => $id]));
            } else {
                $this->addFlash("warning", "Partita non trovata...");

                return $this->redirect($this->generateUrl("app"));
            }
        }, $request);
    }

    #[Route('/game/{id}/status/{status}', name: 'app.game.status')]
    public function changeStatus(Request $request, EntityManagerInterface $entityManagerInterface, int $id, string $status): RedirectResponse
    {
        return $this->guardService->Guard(function () use ($entityManagerInterface, $id, $status) {
            $repository = $entityManagerInterface->getRepository(Game::class);
            $game = $repository->findOneBy(["id" => $id]);

            if ($status !== "start" && $status !== "stop" && $status !== "resume") {
                $this->addFlash("danger", "Stato '$status' é invalido!");

                return $this->redirect($this->generateUrl("app.game", ["id" => $id]));
            }

            if ($game) {
                /** @var Game $game */
                $game->setStatus($status);

                if ($status === "start") {
                    $game->setStartTime(new DateTime());
                    $game->setStatus("resume");
                } elseif ($status === "stop" || $status === "start") {
                    $game->setStatusUpdate(new DateTime());
                }

                $entityManagerInterface->persist($game);
                $entityManagerInterface->flush();

                $this->addFlash("info", "Stato della partita cambiato.");

                return $this->redirect($this->generateUrl("app.game", ["id" => $id]));
            } else {
                $this->addFlash("warning", "Partita non trovata...");

                return $this->redirect($this->generateUrl("app"));
            }
        }, $request);
    }
}
