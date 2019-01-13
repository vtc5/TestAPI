<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 29.12.2018
 * Time: 8:51
 */

namespace AppBundle\Services;

use AppBundle\Entity\Company;
use AppBundle\Entity\User;

class UserService extends BaseService
{

  public function getUsers() {
    $userList = array();
    $users = $this->em->getRepository('AppBundle:User')->findAll();
    foreach ($users as $user) {
      $userRecord = new \stdClass();
      $userRecord->id = $user->getId();
      $userRecord->name = $user->getName();
      $userRecord->email = $user->getEmail();
      $userRecord->companyName = $user->getCompany()->getName();
      $userRecord->companyid = $user->getCompany()->getId();
      $userList[] = $userRecord;
    }
    return $userList;
  }

  public function getUser($id) {
    $user = $this->em->getRepository('AppBundle:User')
      ->findOneBy(array('id'=>$id));
    if (empty($user)) {
      $user = new User();
      $user->setId(null);
    }
    $userRecord = new \stdClass();
    $userRecord->id = $user->getId();
    $userRecord->name = $user->getName();
    $userRecord->email = $user->getEmail();
    if ($user->getCompany() instanceof Company) {
      $userRecord->companyName = $user->getCompany()->getName();
      $userRecord->companyid = $user->getCompany()->getId();
    } else {
      $userRecord->companyName = '';
      $userRecord->companyid = null;
    }
    return $userRecord;
  }


  public function createUser($object, $id) {
    $this->checkUser($object);
    $response = array();
    if (empty($this->errors)) {
      $user = new User();
      if (!empty($id)) {
        $user->setId($id);
      }
      $user->setName($object->Name);
      $user->setEmail($object->EMail);
      $company = $this->em->getRepository('AppBundle:Company')
        ->findOneBy(array(
          'id'=>$object->company_id
        ));
      if (empty($company)) {
        $this->createError(7,'Company with id '.$object->company_id." doesn't exist");
      }
      $user->setCompany($company);
      $this->em->persist($user);
      if (empty($this->errors)){
        try {
          $this->em->flush();
        } catch (\Exception $exception) {
          $this->createError($exception->getCode(), $exception->getMessage());
        }
      }
    }
    $response['errors']=$this->errors;
    $response = $this->returnUsers($response, $object);
    return $response;
  }

  public function updateUser($object, $id) {
    $this->checkUser($object);
    $response = array();
    if (empty($this->errors)) {
      $user = $this->em->getRepository('AppBundle:User')
        ->findOneBy(array(
          'id'=>$id
        ));
      if (empty($user)) {
        $this->createError(8,'User woth id '.$id." doesn't exist");
      }
      $company = $this->em->getRepository('AppBundle:Company')
        ->findOneBy(array(
          'id'=>$object->company_id
        ));
      if (empty($company)) {
        $this->createError(9,'Company woth id '.$object->company_id." doesn't exist");
      }
      if (empty($this->errors)){
        $user->setName($object->Name);
        $user->setEmail($object->EMail);
        $user->setCompany($company);
        $this->em->persist($user);
        try {
          $this->em->flush();
        } catch (\Exception $exception) {
          $this->createError($exception->getCode(), $exception->getMessage());
        }
      }
    }
    $response['errors']=$this->errors;
    $response = $this->returnUsers($response, $object);
    return $response;
  }

  public function deleteUser($object, $id) {
    $response = array();
    $user = $this->em->getRepository('AppBundle:User')
      ->findOneBy(array(
        'id'=>$id
      ));
    if (empty($user)) {
      $this->createError(10,'User woth id '.$id." doesn't exist");
    }
    if (empty($this->errors)) {
      $transfers = $this->em->getRepository('AppBundle:TransferLog')
        ->findBy(array('user'=>$user));
      foreach ($transfers as $transfer) {
        $this->em->remove($transfer);
      }
      $this->em->remove($user);
      try {
        $this->em->flush();
      } catch (\Exception $exception) {
        $this->createError($exception->getCode(), $exception->getMessage());
      }
    }
    $response['errors']=$this->errors;
    $response = $this->returnUsers($response, $object);
    return $response;
  }

  protected function returnUsers(array $response, $object) {
    if (is_object($object) and property_exists($object,'returnList') and
      $object->returnList
    ) {
      $response['users']=$this->getUsers();
    }
    return $response;
  }
  protected function checkUser($object) {
    if (property_exists($object,'Name')) {
      if (empty($object->Name)) {
        $this->createError(11,"Field 'Name' is empty");
      }
    }else{
      $this->createError(12,"Field 'Name' doesn't exist");
    }
    if (property_exists($object,'EMail')) {
      if (!filter_var($object->EMail, FILTER_VALIDATE_EMAIL)) {
        $this->createError(13,"Field 'EMail' has wrong E-Mail address");
      }
    } else {
      $this->createError(14,"Field 'EMail' doesn't exist");
    }
    if (!property_exists($object,'company_id')) {
      $this->createError(15,"Field 'company_id' doesn't exist");
    }
  }

}