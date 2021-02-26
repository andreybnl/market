<?php

namespace App\Entity;

use App\Repository\SpeedChannelsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpeedChannelsRepository::class)
 */
class SpeedChannels
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
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $currency_code;

    /**
     * @ORM\Column(type="float")
     */
    private $currency_rate;

    /**
     * @ORM\Column(type="float")
     */
    private $vat_percentage_low;

    /**
     * @ORM\Column(type="float")
     */
    private $vat_percentage_high;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currency_code;
    }

    public function setCurrencyCode(string $currency_code): self
    {
        $this->currency_code = $currency_code;

        return $this;
    }

    public function getCurrencyRate(): ?float
    {
        return $this->currency_rate;
    }

    public function setCurrencyRate(float $currency_rate): self
    {
        $this->currency_rate = $currency_rate;

        return $this;
    }

    public function getVatPercentageLow(): ?float
    {
        return $this->vat_percentage_low;
    }

    public function setVatPercentageLow(float $vat_percentage_low): self
    {
        $this->vat_percentage_low = $vat_percentage_low;

        return $this;
    }

    public function getVatPercentageHigh(): ?float
    {
        return $this->vat_percentage_high;
    }

    public function setVatPercentageHigh(float $vat_percentage_high): self
    {
        $this->vat_percentage_high = $vat_percentage_high;

        return $this;
    }
}
