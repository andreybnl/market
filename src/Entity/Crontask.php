<?php

namespace App\Entity;

use App\Repository\CrontaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CrontaskRepository::class)
 */
class Crontask
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=88)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number"
     * )
     */
    /**
     * @ORM\Column(type="string", length=77)
     */
    private $schedule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $details;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priority;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $retry;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cronStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cronEnd;

    /**
     * @ORM\Column(type="string")
     */
    private $cronDuration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastStatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(string $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getRetry(): ?int
    {
        return $this->retry;
    }

    public function setRetry(?int $retry): self
    {
        $this->retry = $retry;

        return $this;
    }

    public function getCronStart(): ?\DateTimeInterface
    {
        return $this->cronStart;
    }

    public function setCronStart(\DateTimeInterface $cronStart): self
    {
        $this->cronStart = $cronStart;

        return $this;
    }

    public function getCronEnd(): ?\DateTimeInterface
    {
        return $this->cronEnd;
    }

    public function setCronEnd(\DateTimeInterface $cronEnd): self
    {
        $this->cronEnd = $cronEnd;

        return $this;
    }

    public function getCronDuration(): string //changed from dateTime
    {
        return $this->cronDuration;
    }

    public function setCronDuration(string $cronDuration): self //changed from dateTime
    {
        $this->cronDuration = $cronDuration;

        return $this;
    }

    public function getLastStatus(): ?string
    {
        return $this->lastStatus;
    }

    public function setLastStatus(string $lastStatus): self
    {
        $this->lastStatus = $lastStatus;

        return $this;
    }
}
