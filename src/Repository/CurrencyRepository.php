<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Currency>
 *
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function save(Currency $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Currency $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAll(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Currency c'
        );

        return $query->getResult();
    }

    public function findExchangeRate(Currency $sourceCurrency, string $targetCode): ?float
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.exchangeRates', 'er')
            ->andWhere('er.targetCurrency = :targetCode')
            ->andWhere('er.sourceCurrency = :sourceCurrency')
            ->setParameter('targetCode', $targetCode)
            ->setParameter('sourceCurrency', $sourceCurrency);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result ? $result->getExchangeRate() : null;
    }
}
