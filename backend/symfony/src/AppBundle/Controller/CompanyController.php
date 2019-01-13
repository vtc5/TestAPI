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

class CompanyController extends Controller
{

  public function companiesAction(Request $request) {
    $service = $this->get('company_service');
    return new JsonResponse($service->getCompanies());
  }

  public function companyAction(Request $request, $id) {
    $service = $this->get('company_service');
    return new JsonResponse($service->getCompany($id));
  }

  public function createAction(Request $request, $id = 0) {
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
    $ret = $service->createCompany($object, $id);
    return new JsonResponse($ret);
  }

  public function updateAction(Request $request, $id) {
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
    $ret = $service->updateCompany($object, $id);
    return new JsonResponse($ret);
  }

  public function deleteAction(Request $request, $id) {
    $service = $this->get('company_service');
    $object = json_decode($request->getContent());
    $ret = $service->deleteCompany($object, $id);
    return new JsonResponse($ret);
  }


  // for select2
  public function companySelectAction(Request $request) {
    $term = trim(strip_tags($request->get('term')));
    $limit = 0 + trim(strip_tags($request->get('maxRows')));
    $page = trim(strip_tags($request->get('page')));

    $service = $this->get('company_service');
    $companiesList = $service->companySelect($term, $limit, $page);
    return  new JsonResponse($companiesList);
  }

  // for select2 vue
  public function companySelectVueAction(Request $request) {
    $term = trim(strip_tags($request->get('term')));
    $limit = 0 + trim(strip_tags($request->get('maxRows')));
    $page = trim(strip_tags($request->get('page')));

    $service = $this->get('company_service');
    $companiesList = $service->companySelectVue($term, $limit, $page);
    return  new JsonResponse($companiesList);
  }

}