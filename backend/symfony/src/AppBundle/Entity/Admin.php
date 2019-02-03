<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 21.01.2019
 * Time: 22:48
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity()
 * @ORM\Table(name="admins")
 */
class Admin
{

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=25, unique=true)
   */
  protected $username;

  /**
   * @ORM\Column(type="string", length=254)
   */
  protected $password;

  /**
   * @ORM\Column(type="string", length=254, unique=true)
   */
  protected $email;

  /**
   * @ORM\Column(name="is_active", type="boolean")
   */
  protected $isActive;

  /**
   * @ORM\Column(type="string", length=64, nullable=true)
   */
  protected $apiKey;


  /**
   * @ORM\Column(type="datetime", nullable=false)
   */
  protected $created;

  /**
   * @ORM\Column(type="datetime", nullable=false)
   */
  protected $updated;

  public function __construct()
  {
    $this->isActive = true;
    $this->created = new \DateTime();
    $this->updated = new \DateTime();
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param integer $id
   * @return Admin
   */
  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  /**
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * @param string $username
   * @return Admin
   */
  public function setUsername($username)
  {
    $this->username = $username;
    return $this;
  }

  /**
   * @return string
   */
  public function checkPassword($pass)
  {
    return password_verify($pass, $this->password);
  }

  /**
   * @param string $password
   * @return Admin
   */
  public function setPassword($password)
  {
    $this->password = password_hash($password, PASSWORD_BCRYPT );
    return $this;
  }

  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param string $email
   * @return Admin
   */
  public function setEmail($email)
  {
    $this->email = $email;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getisActive()
  {
    return $this->isActive;
  }

  /**
   * @param boolean $isActive
   * @return Admin
   */
  public function setIsActive($isActive)
  {
    $this->isActive = $isActive;
    return $this;
  }

  /**
   * @return string
   */
  public function getApiKey()
  {
    return urlencode($this->apiKey);
  }

  public function geterateApiKey() {
    $this->apiKey = random_bytes(64);
  }

  public function checkApiKey($key) {
    return $this->apiKey==urldecode($key);
  }

  /**
   * @return \DateTime
   */
  public function getCreated()
  {
    return $this->created;
  }

  /**
   * @return \DateTime
   */
  public function getUpdated()
  {
    return $this->updated;
  }


}