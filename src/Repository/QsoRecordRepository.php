<?php

namespace App\Repository;

use App\Entity\QsoRecord;
use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QsoRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method QsoRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method QsoRecord[]    findAll()
 * @method QsoRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QsoRecordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QsoRecord::class);
    }

    public function getLogsNotReceived($roundDate)
    {
        $calls = $this->getEntityManager()->getRepository(Log::class)
            ->createQueryBuilder('l2')
            ->select('c.callsign')
            ->distinct('c.callsign')
            ->leftJoin('App\Entity\Callsign', 'c', 'WITH', 'l2.callsignid=c.callsignid')
            ->where(
                'c.callsign LIKE :ly',
                'l2.date = :rdate'
            )
            ->setParameter('rdate', $roundDate)
            ->setParameter('ly', 'LY%')
            ;

        return $this->createQueryBuilder('q')
            ->leftJoin('App\Entity\Log', 'l', 'WITH', 'l.logid=q.logid')
            ->distinct('q.callsign')
            ->select('q.callsign')
            ->where(
                'q.callsign LIKE :ly',
                'l.date = :rdate',
                $this->createQueryBuilder('c')->expr()->notIn('q.callsign',$calls->getDQL())
            )
            ->setParameter('rdate', $roundDate)
            ->setParameter('ly', 'LY%')
            ->getQuery()
            ->getResult()
            ;
    }
}
