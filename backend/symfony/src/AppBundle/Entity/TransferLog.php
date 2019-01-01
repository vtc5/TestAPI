<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 29.12.2018
 * Time: 7:58
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="transfer_log",
 *   indexes={
 *    @ORM\Index(name="transfer_log_date", columns={"date_time"}),
 *   }
 *   )
 */
class TransferLog
{
  /**
   * @ORM\Id
   * @ORM\Column(type="bigint")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\ManyToOne(targetEntity="User")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
   */
  protected $user;

  /**
   * @ORM\Column(name="date_time", type="datetime", nullable=false)
   */
  protected $dateTime;

  /**
   * @ORM\Column(type="string", length=320, nullable=false)
   */
  protected $resourse;

  /**
   * @ORM\Column(type="bigint", nullable=false)
   */
  protected $transfered;

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param integer $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return User
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param User $user
   */
  public function setUser($user)
  {
    $this->user = $user;
  }

  /**
   * @return \DateTime
   */
  public function getDateTime()
  {
    return $this->dateTime;
  }

  /**
   * @param \DateTime $dateTime
   */
  public function setDateTime($dateTime)
  {
    $this->dateTime = $dateTime;
  }

  /**
   * @return string
   */
  public function getResourse()
  {
    return $this->resourse;
  }

  /**
   * @param string $resourse
   */
  public function setResourse($resourse)
  {
    $this->resourse = $resourse;
  }

  /**
   * @return string
   */
  public function getTransfered()
  {
    return $this->transfered;
  }

  /**
   * @param string $transfered
   */
  public function setTransfered($transfered)
  {
    $this->transfered = $transfered;
  }
}