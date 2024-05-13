<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32, type: "string")]
    private ?string $name = null;

    #[ORM\Column(length: 32, type: "string")]
    private ?string $username = null;

    #[ORM\Column(length: 255, type: "text")]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'mainReferee')]
    private Collection $games;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get the value of games
     */ 
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Set the value of games
     */ 
    public function setGames($games)
    {
        $this->games = $games;
    }
}
