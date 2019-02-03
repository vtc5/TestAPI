<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OtherController extends Controller
{

  public function reportAction(Request $request, $month) {
    $key = $request->query->get('key');
    $service = $this->get('report_service');
    $report = $service->createReport($key, $month);
    if (is_null($report)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse($report);
  }

  public function generateAction(Request $request) {
    $key = $request->query->get('key');
    $service = $this->get('generator_service');
    $ret = $service->generate($key);
    if (is_null($ret)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse('Ok');
  }

}
