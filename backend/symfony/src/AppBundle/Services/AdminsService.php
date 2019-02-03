<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 21.01.2019
 * Time: 23:02
 */

namespace AppBundle\Services;


use AppBundle\Entity\Admin;
use AppBundle\Entity\Sessions;
use Doctrine\ORM\EntityManager;

class AdminsService
{

  /** @var EntityManager $em */
  protected $em;

  /**
   * UserService constructor.
   * @param $em
   */
  public function __construct($em)
  {
    $this->em = $em->getEntityManager();
  }

  public function createUser($username, $password, $email='') {
    $admin = $this->em->getRepository('AppBundle:Admin')
      ->findOneBy(array(
        'username'=>$username
      ));
    if (empty($admin)) {
      $admin = new Admin();
      $admin->setUsername($username);
      $admin->setPassword($password);
      $admin->setEmail($email);
      $admin->geterateApiKey();
      $this->em->persist($admin);
      $this->em->flush();
      return $admin;
    }
    return null;
  }

  public function checkUser($key) {
    return $this->em->getRepository('AppBundle:Admin')
      ->findOneBy(array(
        'apiKey'=>urldecode($key),
        'isActive'=>true
      ));
  }

  public function startSession($username, $password) {
    $admin = $this->em->getRepository('AppBundle:Admin')
      ->findOneBy(array(
        'username'=>$username,
        'isActive'=>true
      ));
    if ($admin instanceof Admin and $admin->checkPassword($password)) {
      $session = new Sessions();
      $session->setAdmin($admin);
      $session->geterateSessionId();
      $this->em->persist($session);
      $this->em->flush();
      return urlencode($session->getSessionId());
    }
    return null;
  }

  public function authenticateAPI($object) {
    $sessionId = '';
    if (property_exists($object,'sessionId')) {
      $sessionId = $object->sessionId;
    }
    return $this->authenticateBySession($sessionId);
  }

  public function authenticateBySession($sessionId) {
    $session = $this->em->getRepository('AppBundle:Sessions')
      ->findOneBy(array(
        'sessionId'=>urldecode($sessionId),
        'disabled'=>false
      ));
    if ($session instanceof Sessions and $session->getAdmin()->getisActive()) {
      return $session->getAdmin();
    }
    return null;
  }

  public function stopSession($sessionId) {
    $session = $this->em->getRepository('AppBundle:Sessions')
      ->findOneBy(array(
        'sessionId'=>urldecode($sessionId)
      ));
    if ($session instanceof Sessions) {
      $this->em->remove($session);
      $this->em->flush();
    }
  }
}