<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $symbol = null;

    #[ORM\OneToMany(mappedBy: 'sourceCurrency', targetEntity: ExchangeRate::class)]
    private Collection $exchangeRates;

    #[ORM\OneToMany(mappedBy: 'currency', targetEntity: ExchangeRate::class)]
    private Collection $exchangeRate;

    public function __construct()
    {
        $this->exchangeRates = new ArrayCollection();
        $this->exchangeRate = new ArrayCollection();
    }

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

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return Collection<int, ExchangeRate>
     */
    public function getExchangeRates(): Collection
    {
        return $this->exchangeRates;
    }

    public function addExchangeRate(ExchangeRate $exchangeRate): self
    {
        if (!$this->exchangeRates->contains($exchangeRate)) {
            $this->exchangeRates->add($exchangeRate);
            $exchangeRate->setSourceCurrency($this);
        }

        return $this;
    }

    public function removeExchangeRate(ExchangeRate $exchangeRate): self
    {
        if ($this->exchangeRates->removeElement($exchangeRate)) {
            // set the owning side to null (unless already changed)
            if ($exchangeRate->getSourceCurrency() === $this) {
                $exchangeRate->setSourceCurrency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ExchangeRate>
     */
    public function getExchangeRate(): Collection
    {
        return $this->exchangeRate;
    }
}
