<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OtherController extends Controller
{

  public function reportAction(Request $request, $month) {
    $service = $this->get('report_service');
    $report = $service->createReport($month);
    return new JsonResponse($report);
  }

  public function generateAction(Request $request) {
    $service = $this->get('generator_service');
    $service->generate();
    return new JsonResponse('Ok');
  }

}
