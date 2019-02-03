<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 29.12.2018
 * Time: 8:31
 */

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{

  public function companiesAction(Request $request) {
    $key = $request->query->get('key');
    $service = $this->get('company_service');
    $ret = $service->getCompanies($key);
    if (is_null($ret)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse($ret);
  }

  public function companyAction(Request $request, $id) {
    $key = $request->query->get('key');
    $service = $this->get('company_service');
    $ret = $service->getCompany($key, $id);
    if (is_null($ret)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse($ret);
  }

  public function createAction(Request $request, $id = 0) {
    $key = $request->query->get('key');
    $object = json_decode($request->getContent());
    if (!is_object($object)) {
      return new JsonResponse(array(
        'errors'=>array(
          'code'=>0,
          'message'=>'General network error'
        )
      ));
    }
    $service = $this->get('company_service');
    $ret = $service->createCompany($key, $object, $id);
    if (is_null($ret)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse($ret);
  }

  public function updateAction(Request $request, $id) {
    $key = $request->query->get('key');
    $object = json_decode($request->getContent());
    if (!is_object($object)) {
      return new JsonResponse(array(
        'errors'=>array(
          'code'=>0,
          'message'=>'General network error'
        )
      ));
    }
    $service = $this->get('company_service');
    $ret = $service->updateCompany($key, $object, $id);
    if (is_null($ret)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse($ret);
  }

  public function deleteAction(Request $request, $id) {
    $key = $request->query->get('key');
    $service = $this->get('company_service');
    $object = json_decode($request->getContent());
    if (!is_object($object)) {
      return new JsonResponse(array(
        'errors'=>array(
          'code'=>0,
          'message'=>'General network error'
        )
      ));
    }
    $ret = $service->deleteCompany($key, $object, $id);
    if (is_null($ret)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse($ret);
  }


  // for select2
  public function companySelectAction(Request $request) {
    $key = $request->query->get('key');
    $term = trim(strip_tags($request->get('term')));
    $limit = 0 + trim(strip_tags($request->get('maxRows')));
    $page = trim(strip_tags($request->get('page')));

    $service = $this->get('company_service');
    $companiesList = $service->companySelect($key, $term, $limit, $page);
    if (is_null($companiesList)) {
      $response = new JsonResponse('Error');
      $response->setStatusCode(400);
    } else {
      $response = new JsonResponse($companiesList);
    }
    return $response;
  }

  // for select2 vue
  public function companySelectVueAction(Request $request) {
    $key = $request->query->get('key');
    $term = trim(strip_tags($request->get('term')));
    $limit = 0 + trim(strip_tags($request->get('maxRows')));
    $page = trim(strip_tags($request->get('page')));

    $service = $this->get('company_service');
    $companiesList = $service->companySelectVue($key, $term, $limit, $page);
    if (is_null($companiesList)) {
      $response = new JsonResponse('Error');
      $response->setStatusCode(400);
    } else {
      $response = new JsonResponse($companiesList);
    }
    return $response;
  }

}