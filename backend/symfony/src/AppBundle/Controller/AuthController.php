<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 22.01.2019
 * Time: 7:43
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{

  public function authAction(Request $request) {
    $object = json_decode($request->getContent());
    if (!is_object($object)) {
      return new JsonResponse(array(
        'errors'=>array(
          'code'=>0,
          'message'=>'General network error'
        )
      ));
    }
    $username = '';
    $password = '';
    if (property_exists($object,'username')) {
      $username = $object->username;
    }
    if (property_exists($object,'password')) {
      $password = $object->password;
    }
    $service = $this->get('admins_service');
    $ret = $service->startSession($username, $password);
    if (is_null($ret)) {
      $response = new JsonResponse(array(
        'sessionId'=>null,
        'errors'=>array("Don't authenticate!")
      ));
      $response->setStatusCode(400);
      return $response;
    }
    return new JsonResponse(array(
      'sessionId'=>$ret,
      'errors'=>array()
    ));
  }

  public function checkAction(Request $request) {
    $key = $request->query->get('key');
    $service = $this->get('admins_service');
    if ($service->authenticateBySession($key) instanceof Admin) {
      return new Response('Ok');
    }
    $response = new Response('Error');
    $response->setStatusCode(400);
    return $response;
  }

  public function logoutAction(Request $request) {
    $object = json_decode($request->getContent());
    if (!is_object($object)) {
      return new JsonResponse(array(
        'errors'=>array(
          'code'=>0,
          'message'=>'General network error'
        )
      ));
    }
    $sessionId = '';
    if (property_exists($object,'sessionId')) {
      $sessionId = $object->sessionId;
    }
    $service = $this->get('admins_service');
    $service->stopSession($sessionId);
    return new Response('Ok');
  }

}