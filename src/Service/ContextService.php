<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;
use App\Entity\User;

class ContextService
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function user(int $id): User|null
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    /**
     * @return Game[]
     */
    public function gamesDone(): array
    {
        return $this->entityManager->getRepository(Game::class)->findBy([]);
    }

    /**
     * @return Game[]
     */
    public function gamesInProgress(): array
    {
        return $this->entityManager->getRepository(Game::class)->findBy([]);
    }
}
