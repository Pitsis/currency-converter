<?php

namespace App\Entity;

use App\Repository\ExchangeRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exchangeRates')]
    private ?Currency $source_currency = null;

    #[ORM\ManyToOne(inversedBy: 'exchangeRates')]
    private ?Currency $target_currency = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4)]
    private ?string $rate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceCurrency(): ?Currency
    {
        return $this->source_currency;
    }

    public function setSourceCurrency(?Currency $source_currency): self
    {
        $this->source_currency = $source_currency;

        return $this;
    }

    public function getTargetCurrency(): ?Currency
    {
        return $this->target_currency;
    }

    public function setTargetCurrency(?Currency $target_currency): self
    {
        $this->target_currency = $target_currency;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
