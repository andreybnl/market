<?php

namespace App\Entity;

use App\Repository\ManualLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ManualLogRepository::class)
 */
class ManualLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $request;

    /**
     * @ORM\Column(type="string", length=11255, nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=111111, nullable=true)
     */
    private $answer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $result;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequest(): ?string
    {
        return $this->request;
    }

    public function setRequest(?string $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }
}
