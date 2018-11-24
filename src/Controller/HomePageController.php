<?php
// src/Controller/HomePageController.php
namespace App\Controller;

use App\Entity\Logs;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomePageController extends AbstractController
{
     /**
      * @Route("/")
      */
    public function index()
    {
        /* $repository = $this->getDoctrine()->getRepository(Logs::class);
         * $logs = $repository->find(1);
         */
        $lastMonthStats = $this->getLastMonthStats();
        dump($lastMonthStats);
        return $this->render('home.html.twig',array(
          'lastMonthStats' => $lastMonthStats
        ));
    }

    public function getLastMonthStats()
    {
      /*
      SELECT count(*), bands.band_freq FROM `logs`
      left join bands on bands.bandID=logs.bandID
      where date > (NOW() - INTERVAL 1 month)
      group by bands.bandID
      */
      $c = $this->getDoctrine()->getManager()->getConnection();
      $qb = $c->createQueryBuilder('l')
        ->from('logs','l')
        ->select('count(l.logID) as c')
        ->leftJoin('l','bands','b','b.bandID=l.bandID')
        ->addSelect('b.band_freq')
        ->where('l.date > (NOW() - INTERVAL 1 month)')
        ->groupBy('b.bandID');
      $em = $this->getDoctrine()->getManager()->createQuery(
        $qb->execute()->queryString
      );

      return $em;
    }
}
