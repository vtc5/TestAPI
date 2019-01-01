<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 29.12.2018
 * Time: 7:46
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="companies")
 */
class Company
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
   * @ORM\Column(type="bigint", nullable=true)
   */
  protected $quota;

  /**
   * @ORM\OneToMany(targetEntity="User", mappedBy="company")
   */
  protected $users;

  /**
   * @ORM\Column(type="datetime", nullable=false)
   */
  protected $created;

  /**
   * @ORM\Column(type="datetime", nullable=false)
   */
  protected $updated;

  /**
   * Company constructor.
   */
  public function __construct()
  {
    $this->users = new ArrayCollection();
    $this->created = new \DateTime();
    $this->updated = new \DateTime();
  }


  public function __toString()
  {
    return $this->getName();
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
   * @return Company
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
   * @return Company
   */
  public function setName($name)
  {
    $this->name = $name;
    return $this;
  }

  /**
   * @return integer
   */
  public function getQuota()
  {
    return $this->quota;
  }

  /**
   * @param integer $quota
   * @return Company
   */
  public function setQuota($quota)
  {
    $this->quota = $quota;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getUsers()
  {
    return $this->users;
  }

  /**
   * @param mixed $users
   * @return Company
   */
  public function setUsers($users)
  {
    $this->users = $users;
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
   * @return Company
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
   * @return Company
   */
  public function setUpdated($updated)
  {
    $this->updated = $updated;
    return $this;
  }


}