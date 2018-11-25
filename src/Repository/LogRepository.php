<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Log::class);
    }

    // /**
    //  * @return Logs[] Returns an array of Logs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    private static function subtractOneMonth($date)
    {
        $_d = new \DateTime($date);
        return $_d->modify('-1 month');
    }

    public function findCallsignsWithoutLog()
    {

        /*  select distinct callsign from qsorecords q
            LEFT JOIN logs l on q.logID=l.logID
            where q.callsign like 'LY%'
            and l.date > '2018-03-01'
            and callsign not in (
                SELECT distinct c.callsign FROM logs l
                LEFT JOIN callsigns c on l.callsignID=c.callsignID
                where c.callsign like 'LY%'
                and l.date > '2018-03-01'
            )
        */
    }
    public function findLastCallsigns($maxres) {
        return $this->createQueryBuilder('l')
            ->leftJoin('App\Entity\Callsigns','c','WITH','c.callsignid=l.callsignid')
            ->select('c.callsign')
            ->orderBy('l.date', 'DESC')
            ->setMaxResults($maxres)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLastDate()
    {
        return $this->createQueryBuilder('l')
            ->select('MAX(l.date)')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findLastMonthStats($date)
    {
      return $this->createQueryBuilder('l')
        ->select('count(l.logid) as count')
        ->leftJoin('App\Entity\Bands','b','WITH','b.bandid=l.bandid')
        ->addSelect('b.bandFreq')
        ->where('l.date > :since')
        ->setParameter('since', $this->subtractOneMonth($date))
        ->groupBy('b.bandid')
        ->getQuery()
        ->getResult()
        ;
    }

}
