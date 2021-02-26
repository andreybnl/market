<?php

namespace App\Entity;

use App\Repository\MarketQueryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarketQueryRepository::class)
 */
class MarketQuery
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
    private $query;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $requestType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Supplier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getRequestType(): ?string
    {
        return $this->requestType;
    }

    public function setRequestType(?string $requestType): self
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function getSupplier(): ?string
    {
        return $this->Supplier;
    }

    public function setSupplier(string $Supplier): self
    {
        $this->Supplier = $Supplier;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
