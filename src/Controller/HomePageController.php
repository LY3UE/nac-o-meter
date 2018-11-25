<?php
// src/Controller/HomePageController.php
namespace App\Controller;

use App\Entity\Log;
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
        $repository = $this->getDoctrine()->getRepository(Log::class);
        $lastCallsigns = $repository->findLastCallsigns(5);
        $lastDate = $repository->findLastDate()[1];
        $lastMonthStats = $repository->findLastMonthStats($lastDate);
        dump($lastDate);

        return $this->render('home.html.twig',array(
          'lastMonthStats' => $lastMonthStats,
          'lastDate' => $lastDate,
          'lastCallsigns' => $lastCallsigns,
          'currentYear' => \DateTime::createFromFormat(
            "Y-m-d", $lastDate
            )->format('Y')
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
    }
}
