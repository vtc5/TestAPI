<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 29.12.2018
 * Time: 8:31
 */

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

  public function usersAction(Request $request) {
    $service = $this->get('user_service');
    return new JsonResponse($service->getUsers());
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
    $service = $this->get('user_service');
    $ret = $service->createUser($object, $id);
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
    $service = $this->get('user_service');
    $ret = $service->updateUser($object, $id);
    return new JsonResponse($ret);
  }

  public function deleteAction(Request $request, $id) {
    $service = $this->get('user_service');
    $object = json_decode($request->getContent());
    $ret = $service->deleteUser($object, $id);
    return new JsonResponse($ret);
  }

}