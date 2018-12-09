<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Callsign;
use App\Form\CallsignSearch;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CallSignInfoController extends AbstractController
{
    /**
     * @Route("/call/{callsign}", name="call_search")
     */
    public function callsignSearch($callsign)
    {
        $callsignSearchForm = $this->createForm(CallsignSearch::class);

        return $this->render(
              'callsign.html.twig',
              array(
            'callSearch' => $callsignSearchForm->createView()
          )
        );
    }
}
