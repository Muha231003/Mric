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
        $query = $this->entityManager->getConnection()->prepare("SELECT id FROM `game` where TIMESTAMPDIFF(MINUTE, start_time, NOW()) >= 90;");
        $ids = $query->executeQuery()->fetchAll();
        $ids = array_values(array_map(function ($i) {
            return $i["id"];
        }, $ids));

        return $this->entityManager->getRepository(Game::class)->findBy(["id" => $ids]);
    }

    /**
     * @return Game[]
     */
    public function gamesInProgress(): array
    {
        $query = $this->entityManager->getConnection()->prepare("SELECT id FROM `game` where TIMESTAMPDIFF(MINUTE, start_time, NOW()) < 90 OR start_time IS NULL;");
        $ids = $query->executeQuery()->fetchAll();
        $ids = array_values(array_map(function ($i) {
            return $i["id"];
        }, $ids));

        return $this->entityManager->getRepository(Game::class)->findBy(["id" => $ids]);
    }
}
