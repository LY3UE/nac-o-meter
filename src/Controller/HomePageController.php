<?php
// src/Controller/HomePageController.php
namespace App\Controller;

use App\Entity\Log;
use App\Entity\Round;
use App\Entity\Callsign;
use App\Entity\QsoRecord;
use App\Form\CallsignSearch;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $logRepository = $this->getDoctrine()->getRepository(Log::class);
        $qsoRepository = $this->getDoctrine()->getRepository(QsoRecord::class);
        $roundRepository = $this->getDoctrine()->getRepository(Round::class);

        $lastCallsigns = $logRepository->findLastCallsigns(5);
        $lastDate = $logRepository->findLastDate()[1];
        $lastMonthStats = $logRepository->findLastMonthStats($lastDate);
        $lastRounds = $roundRepository->findLastRoundDates($lastDate);

        $logsNotReceived = [];
        foreach ($lastRounds as $lastRound) {
          $dateStr = $lastRound['date']->format('Y-m-d');
          $logsNotReceived[$dateStr] = $qsoRepository->getLogsNotReceived($dateStr);
        }

        dump($logsNotReceived);

        /* TODO: move below block to a separate route */

        $callsignSearchForm = $this->createForm(CallsignSearch::class);
        $callsignSearchForm->handleRequest($request);
        if ($callsignSearchForm->isSubmitted() && $callsignSearchForm->isValid()) {
            $data = $callsignSearchForm->getData();
            return $this->redirectToRoute(
              'call_search',
              array(
                'callsign' => $data['callsign']
            )
          );
        }

        return $this->render('home.html.twig', array(
          'lastMonthStats' => $lastMonthStats,
          'lastDate' => $lastDate,
          'lastRounds' => $lastRounds,
          'lastCallsigns' => $lastCallsigns,
          'logsNotReceived' => $logsNotReceived,
          'callSearch' => $callsignSearchForm->createView(),
          'currentYear' => \DateTime::createFromFormat(
            "Y-m-d",
              $lastDate
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
