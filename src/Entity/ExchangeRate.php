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
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $sourceCurrency = null;

    #[ORM\ManyToOne(inversedBy: 'exchangeRates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $targetCurrency = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 6)]
    private ?string $rate = null;

    #[ORM\ManyToOne(inversedBy: 'exchangeRate')]
    private ?Currency $currency = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceCurrency(): ?Currency
    {
        return $this->sourceCurrency;
    }

    public function setSourceCurrency(?Currency $sourceCurrency): self
    {
        $this->sourceCurrency = $sourceCurrency;

        return $this;
    }

    public function getTargetCurrency(): ?Currency
    {
        return $this->targetCurrency;
    }

    public function setTargetCurrency(?Currency $targetCurrency): self
    {
        $this->targetCurrency = $targetCurrency;

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

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
