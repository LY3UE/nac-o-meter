<?php

namespace App\Controller;

use App\Form\CallsignSearch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\ResultParser;

class ResultsController extends AbstractController
{
    /**
     * @Route("/results/{year}/{band}", name="results", defaults={"year"="","band"=""})
     */

    public function index()
    {
        $callsignSearchForm = $this->createForm(CallsignSearch::class);
        $results = new ResultParser();
        $resultYears = $results->getAllYears();

        // $results = $rp->getMonthResultByCall('LY2EN','2017',4,'144');
        dump($results);
        return $this->render('results/index.html.twig', [
            'result_years' => $resultYears,
            'controller_name' => 'ResultsController',
            'callSearch' => $callsignSearchForm->createView(),
        ]);
    }
}
