<?php

namespace App\Repository;

use App\Entity\Callsign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Callsign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Callsign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Callsign[]    findAll()
 * @method Callsign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CallsignRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Callsign::class);
    }
    
}
