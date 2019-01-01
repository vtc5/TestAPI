<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 29.12.2018
 * Time: 7:52
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User
{

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=100, nullable=false)
   */
  protected $name;

  /**
   * @ORM\Column(type="string", length=320, nullable=false)
   */
  protected $email;

  /**
   * @ORM\ManyToOne(targetEntity="Company", inversedBy="users")
   * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
   */
  protected $company;

  /**
   * @ORM\Column(type="datetime", nullable=false)
   */
  protected $created;

  /**
   * @ORM\Column(type="datetime", nullable=false)
   */
  protected $updated;

  /**
   * User constructor.
   */
  public function __construct()
  {
    $this->created = new \DateTime();
    $this->updated = new \DateTime();
  }


  public function __toString()
  {
    return $this->getName().' ('.$this->getCompany()->getName().')';
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
   * @return User
   */
  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param string $name
   * @return User
   */
  public function setName($name)
  {
    $this->name = $name;
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
   * @return User
   */
  public function setEmail($email)
  {
    $this->email = $email;
    return $this;
  }

  /**
   * @return Company
   */
  public function getCompany()
  {
    return $this->company;
  }

  /**
   * @param Company $company
   * @return User
   */
  public function setCompany($company)
  {
    $this->company = $company;
    return $this;
  }

  /**
   * @return \DateTime
   */
  public function getCreated()
  {
    return $this->created;
  }

  /**
   * @param \DateTime $created
   * @return User
   */
  public function setCreated($created)
  {
    $this->created = $created;
    return $this;
  }

  /**
   * @return \DateTime
   */
  public function getUpdated()
  {
    return $this->updated;
  }

  /**
   * @param \DateTime $updated
   * @return User
   */
  public function setUpdated($updated)
  {
    $this->updated = $updated;
    return $this;
  }

}