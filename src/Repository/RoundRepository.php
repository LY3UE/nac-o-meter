<?php

namespace App\Repository;

use App\Entity\Round;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Round|null find($id, $lockMode = null, $lockVersion = null)
 * @method Round|null findOneBy(array $criteria, array $orderBy = null)
 * @method Round[]    findAll()
 * @method Round[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoundRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Round::class);
    }

    public function findLastRoundDates($beforeDate) {
        return $this->createQueryBuilder('r')
            ->where('r.date <= :since')
            ->orderBy('r.date','DESC')
            ->setParameter('since', $beforeDate)
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
            ;
    }
}
