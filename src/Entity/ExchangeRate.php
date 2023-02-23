<?php

namespace App\Entity;

use App\Repository\ExchangeRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'exchangerate:get']),
        new GetCollection(normalizationContext: ['groups' => 'exchangerate:get']),
        new Post(normalizationContext: ['groups' => 'exchangerate:post']),
        new Delete(normalizationContext: ['groups' => 'exchangerate:delete']),
        new Patch(normalizationContext: ['groups' => 'exchangerate:patch']),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
)]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['exchangerate:get', 'exchangerate:delete'])]
    private ?int $id = null;

    /**
     * Represents a ManyToOne relationship between the ExchangeRate entity and the Currency entity.
     */
    #[ORM\ManyToOne(inversedBy: 'exchangeRates')]
    #[Groups(['exchangerate:get', 'exchangerate:post', 'exchangerate:patch'])]
    private ?Currency $source_currency = null;

    /**
     * Represents a ManyToOne relationship between the ExchangeRate entity and the Currency entity.
     */
    #[ORM\ManyToOne(inversedBy: 'exchangeRates')]
    #[Groups(['exchangerate:get', 'exchangerate:post', 'exchangerate:patch'])]
    private ?Currency $target_currency = null;

    /**
     * A decimal column that represents the exchange rate between two currencies.
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4)]
    #[Groups(['exchangerate:get', 'exchangerate:post', 'exchangerate:patch'])]
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
