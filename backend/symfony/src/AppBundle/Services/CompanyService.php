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

class CompanyService extends BaseService
{

  public function getCompanies() {
    $companiesList = array();
    $companies = $this->em->getRepository('AppBundle:Company')->findAll();
    foreach ($companies as $company) {
      $companyRecord = new \stdClass();
      $companyRecord->id = $company->getId();
      $companyRecord->name = $company->getName();
      $companyRecord->quota = $company->getQuota()/(1024*1024*1024);
      $companiesList[] = $companyRecord;
    }
    return $companiesList;
  }

  public function createCompany($object, $id) {
    $this->checkCompany($object);
    $response = array();
    if (empty($this->errors)) {
      $company = new Company();
      if (!empty($id)) {
        $company->setId($id);
      }
      $company->setName($object->Name);
      $company->setQuota($object->Quota*(1024*1024*1024));
      $this->em->persist($company);
      if (empty($this->errors)){
        try {
          $this->em->flush();
        } catch (\Exception $exception) {
          $this->createError($exception->getCode(), $exception->getMessage());
        }
      }
    }
    $response['errors']=$this->errors;
    $response = $this->returnCompanies($response, $object);
    return $response;
  }

  public function updateCompany($object, $id) {
    $this->checkCompany($object);
    $response = array();
    if (empty($this->errors)) {
      $company = $this->em->getRepository('AppBundle:Company')
        ->findOneBy(array(
          'id'=>$id
        ));
      if (empty($company)) {
        $this->createError(1,'Company with id '.$id." doesn't exist");
      }
      if (empty($this->errors)){
        $company->setName($object->Name);
        $company->setQuota($object->Quota*(1024*1024*1024));
        $this->em->persist($company);
        try {
          $this->em->flush();
        } catch (\Exception $exception) {
          $this->createError($exception->getCode(), $exception->getMessage());
        }
      }
    }
    $response['errors']=$this->errors;
    $response = $this->returnCompanies($response, $object);
    return $response;
  }

  public function deleteCompany($object, $id) {
    $response = array();
    $company = $this->em->getRepository('AppBundle:Company')
      ->findOneBy(array(
        'id'=>$id
      ));
    if (empty($company)) {
      $this->createError(2,'Company with id '.$id." doesn't exist");
    }
    if (empty($this->errors)) {
      $user = $this->em->getRepository('AppBundle:User')
        ->findOneBy(array(
          'company' => $company
        ));
      if ($user instanceof User) {
        $this->createError(3,'Company with id '.$id." has users!");
      }
    }
    if (empty($this->errors)) {
      $this->em->remove($company);
      try {
        $this->em->flush();
      } catch (\Exception $exception) {
        $this->createError($exception->getCode(), $exception->getMessage());
      }
    }
    $response['errors']=$this->errors;
    $response = $this->returnCompanies($response, $object);
    return $response;
  }

  public function companySelect($term, $limit, $page) {
    $query = $this->em->getConnection()->prepare("
        select id, name
        from companies
        where name LIKE :search
        LIMIT 25 
      ");
    $query->bindValue('search', '%'.$term.'%');
    $query->execute();
    $entities = $query->fetchAll();

    $companies = array();
    foreach ($entities as $entity)
    {
      $companies[] = array(
        'id' => $entity['id'],
        'text' => $entity['name']
      );
    }
    return array(
      'results'=>$companies,
      'pagination'=>array(
        'more'=>false
      )
    );
  }

  protected function returnCompanies(array $response, $object) {
    if (is_object($object) and property_exists($object,'returnList') and
      $object->returnList
    ) {
      $response['companies']=$this->getCompanies();
    }
    return $response;
  }
  protected function checkCompany($object) {
    if (property_exists($object,'Name')) {
      if (empty($object->Name)) {
        $this->createError(4,"Field 'Name' is empty");
      }
    }else{
      $this->createError(5,"Field 'Name' doesn't exist");
    }
    if (!property_exists($object,'Quota')) {
      $this->createError(6,"Field 'Quota' doesn't exist");
    }
  }


}