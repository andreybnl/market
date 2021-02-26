<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SupplierRepository::class)
 */
class Supplier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $oauth2_key;

    /**
     * @ORM\Column(type="string", length=258, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $token_created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $grant_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $api_url;


    /**
     * @ORM\Column(type="integer")
     */
    private $delay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token_url;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOauth2Key(): ?string
    {
        return $this->oauth2_key;
    }

    public function setOauth2Key(?string $oauth2_key): self
    {
        $this->oauth2_key = $oauth2_key;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenCreated(): ?\DateTimeInterface
    {
        return $this->token_created;
    }

    public function setTokenCreated(?\DateTimeInterface $token_created): self
    {
        $this->token_created = $token_created;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getGrantType(): ?string
    {
        return $this->grant_type;
    }

    public function setGrantType(?string $grant_type): self
    {
        $this->grant_type = $grant_type;

        return $this;
    }

    public function getApiUrl(): ?string
    {
        return $this->api_url;
    }

    public function setApiUrl(string $api_url): self
    {
        $this->api_url = $api_url;

        return $this;
    }

    public function getDelay(): ?int
    {
        return $this->delay;
    }

    public function setDelay(int $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    public function getTokenUrl(): ?string
    {
        return $this->token_url;
    }

    public function setTokenUrl(?string $token_url): self
    {
        $this->token_url = $token_url;

        return $this;
    }
}