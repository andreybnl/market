<?php

namespace App\Entity;

use App\Repository\QuenyLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuenyLogRepository::class)
 */
class QuenyLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $quent;

    /**
     * @ORM\Column(type="text")
     */
    private $answer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_time;

    /**
     * @ORM\Column(type="integer")
     */
    private $responce_code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuent(): ?string
    {
        return $this->quent;
    }

    public function setQuent(string $quent): self
    {
        $this->quent = $quent;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->date_time;
    }

    public function setDateTime(\DateTimeInterface $date_time): self
    {
        $this->date_time = $date_time;

        return $this;
    }

    public function getResponceCode(): ?int
    {
        return $this->responce_code;
    }

    public function setResponceCode(int $responce_code): self
    {
        $this->responce_code = $responce_code;

        return $this;
    }
}
