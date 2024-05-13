<?php

namespace App\Entity;

use App\Repository\GameRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "games")]
    private ?User $mainReferee = null;

    #[ORM\Column(length: 32, type: "string")]
    private ?string $location = null;

    #[ORM\Column(length: 32, type: "string")]
    private ?string $localTeam = null;

    #[ORM\Column(length: 32, type: "string")]
    private ?string $guestTeam = null;

    #[ORM\Column(length: 32, type: "string")]
    private ?string $subReferee = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTime $date = null;

    #[ORM\OneToMany(targetEntity: GameEvent::class, mappedBy: "game")]
    private ?Collection $gameEvents = null;

    #[ORM\Column(length: 32, type: "string", nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $startTime = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $statusUpdate = null;

    public function __construct()
    {
        $this->gameEvents = new ArrayCollection();
    }

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
     */
    public function setMainReferee($mainReferee)
    {
        $this->mainReferee = $mainReferee;
    }

    /**
     * Get the value of gameEvents
     */
    public function getGameEvents()
    {
        return $this->gameEvents;
    }

    /**
     * Set the value of gameEvents
     */
    public function setGameEvents(ArrayCollection $gameEvents)
    {
        $this->gameEvents = $gameEvents;
    }

    public function addEvent(GameEvent $event)
    {
        $this->gameEvents->add($event);
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get the value of statusUpdate
     */
    public function getStatusUpdate()
    {
        return $this->statusUpdate;
    }

    /**
     * Set the value of statusUpdate
     */
    public function setStatusUpdate($statusUpdate)
    {
        $this->statusUpdate = $statusUpdate;
    }

    /**
     * Get the value of startTime
     */ 
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set the value of startTime
     */ 
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }
}
