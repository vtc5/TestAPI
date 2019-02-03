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
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

  public function usersAction(Request $request) {
    $key = $request->query->get('key');
    $service = $this->get('user_service');
    $ret = $service->getUsers($key);
    if (is_null($ret)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse($ret);
  }

  public function userAction(Request $request, $id) {
    $key = $request->query->get('key');
    $service = $this->get('user_service');
    $ret = $service->getUser($key, $id);
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
    $service = $this->get('user_service');
    $ret = $service->createUser($key, $object, $id);
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
    $service = $this->get('user_service');
    $ret = $service->updateUser($key, $object, $id);
    if (is_null($ret)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse($ret);
  }

  public function deleteAction(Request $request, $id) {
    $key = $request->query->get('key');
    $service = $this->get('user_service');
    $object = json_decode($request->getContent());
    $ret = $service->deleteUser($key, $object, $id);
    if (is_null($ret)) {
      $response = new Response('Error');
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse($ret);
  }

}