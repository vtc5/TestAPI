<?php
/**
 * Created by PhpStorm.
 * User: vtc
 * Date: 21.01.2019
 * Time: 23:22
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity()
 * @ORM\Table(name="sessions")
 */
class Sessions
{

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\ManyToOne(targetEntity="Admin")
   */
  protected $admin;

  /**
   * @ORM\Column(type="binary", length=64, unique=true)
   */
  protected $sessionId;

  /**
   * @ORM\Column(type="boolean", nullable=false)
   */
  protected $online;

  /**
   * @ORM\Column(type="boolean", nullable=false)
   */
  protected $disabled;

  /**
   * @ORM\Column(name="s",type="datetime", nullable=true)
   */
  protected $sessionStart;

  /**
   * @ORM\Column(name="session_end",type="datetime", nullable=true)
   */
  protected $sessionEnd;

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
    $this->created = new \DateTime();
    $this->updated = new \DateTime();
    $this->online = false;
    $this->disabled = false;
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
   * @return Sessions
   */
  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  /**
   * @return Admin
   */
  public function getAdmin()
  {
    return $this->admin;
  }

  /**
   * @param Admin $admin
   * @return Sessions
   */
  public function setAdmin($admin)
  {
    $this->admin = $admin;
    return $this;
  }

  /**
   * @return string
   */
  public function getSessionId()
  {
    return $this->sessionId;
  }

  /**
   * @param string $sessionId
   * @return Sessions
   */
  public function setSessionId($sessionId)
  {
    $this->sessionId = $sessionId;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getOnline()
  {
    return $this->online;
  }

  /**
   * @param boolean $online
   * @return Sessions
   */
  public function setOnline($online)
  {
    $this->online = $online;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getDisabled()
  {
    return $this->disabled;
  }

  /**
   * @param boolean $disabled
   * @return Sessions
   */
  public function setDisabled($disabled)
  {
    $this->disabled = $disabled;
    return $this;
  }

  /**
   * @return \DateTime|null
   */
  public function getSessionStart()
  {
    return $this->sessionStart;
  }

  /**
   * @param \DateTime|null $sessionStart
   * @return Sessions
   */
  public function setSessionStart($sessionStart)
  {
    $this->sessionStart = $sessionStart;
    return $this;
  }

  /**
   * @return \DateTime|null
   */
  public function getSessionEnd()
  {
    return $this->sessionEnd;
  }

  /**
   * @param \DateTime|null $sessionEnd
   * @return Sessions
   */
  public function setSessionEnd($sessionEnd)
  {
    $this->sessionEnd = $sessionEnd;
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
   * @return \DateTime
   */
  public function getUpdated()
  {
    return $this->updated;
  }

  public function geterateSessionId() {
    $this->sessionId = random_bytes(64);
  }

}