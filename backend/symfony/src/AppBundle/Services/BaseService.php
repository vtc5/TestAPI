<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 29.12.2018
 * Time: 10:43
 */

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;


class BaseService
{
  /** @var EntityManager $em */
  protected $em;

  protected $errors = array();

  /**
   * UserService constructor.
   * @param $em
   */
  public function __construct($em)
  {
    $this->em = $em->getEntityManager();
  }

  protected function createError($code, $message) {
    $error = new \stdClass();
    $error->code = $code;
    $error->message = $message;
    $this->errors[] = $error;
  }

}