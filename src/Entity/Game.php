<?php

namespace App\Entity;

use App\Repository\GameRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $mainReferee;

    #[ORM\Column(length: 32)]
    private ?string $location = null;

    #[ORM\Column(length: 32)]
    private ?string $localTeam = null;

    #[ORM\Column(length: 32)]
    private ?string $guestTeam = null;

    #[ORM\Column(length: 32)]
    private ?string $subReferee = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTime $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the value of location
     *
     * @return  self
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Get the value of localTeam
     */
    public function getLocalTeam()
    {
        return $this->localTeam;
    }

    /**
     * Set the value of localTeam
     *
     * @return  self
     */
    public function setLocalTeam($localTeam)
    {
        $this->localTeam = $localTeam;
    }

    /**
     * Get the value of guestTeam
     */
    public function getGuestTeam()
    {
        return $this->guestTeam;
    }

    /**
     * Set the value of guestTeam
     *
     * @return  self
     */
    public function setGuestTeam($guestTeam)
    {
        $this->guestTeam = $guestTeam;
    }

    /**
     * Get the value of referee
     */
    public function getSubReferee()
    {
        return $this->subReferee;
    }

    /**
     * Set the value of referee
     *
     * @return  self
     */
    public function setSubReferee($subReferee)
    {
        $this->subReferee = $subReferee;
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get the value of mainReferee
     */
    public function getMainReferee()
    {
        return $this->mainReferee;
    }

    /**
     * Set the value of mainReferee
     *
     * @return  self
     */
    public function setMainReferee($mainReferee)
    {
        $this->mainReferee = $mainReferee;

        return $this;
    }
}
