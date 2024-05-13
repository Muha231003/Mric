<?php

namespace App\Entity;

use App\Repository\GameEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameEventRepository::class)]
class GameEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: "gameEvents")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column(length: 16, type: "string")]
    private ?string $type = null;

    #[ORM\Column(type: "json")]
    private ?array $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set the value of game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     */ 
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Get the value of value
     */ 
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value
     */ 
    public function setValue($value)
    {
        $this->value = $value;
    }
}
