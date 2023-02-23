<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'currency:get']),
        new GetCollection(normalizationContext: ['groups' => 'currency:get']),
        new Post(denormalizationContext: ['groups' => 'currency:post']),
        new Delete(normalizationContext: ['groups' => 'currency:delete']),
        new Patch(denormalizationContext: ['groups' => 'currency:patch']),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['currency:get', 'currency:delete'])]
    private ?int $id = null;

    /**
     * Represents the @var name of a currency
     */
    #[ORM\Column(length: 255)]
    #[Groups(['currency:get', 'currency:post', 'currency:patch'])]
    private ?string $name = null;

    /**
     * Represents the @var code of a currency
     */
    #[ORM\Column(length: 255)]
    #[Groups(['currency:get', 'currency:post', 'currency:patch'])]
    private ?string $code = null;

    /**
     * Represents the @var symbol of a currency
     */
    #[ORM\Column(length: 255)]
    #[Groups(['currency:get', 'currency:post', 'currency:patch'])]
    private ?string $symbol = null;

    /**
     * A OneToMany relationship with the ExchangeRate class
     */
    #[ORM\OneToMany(mappedBy: 'source_currency', targetEntity: ExchangeRate::class)]
    #[Groups(['currency:get'])]
    private Collection $exchangeRates;

    public function __construct()
    {
        $this->exchangeRates = new ArrayCollection();
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
}
