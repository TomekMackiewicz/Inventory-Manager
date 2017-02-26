<?php

namespace InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Action
 * @ORM\Table(name="actions")
 * @ORM\Entity(repositoryClass="InventoryBundle\Repository\ActionRepository")
 */
class Action {
  /**
   * @var int
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var int
   * @ORM\Column(name="action", type="string", length=3)   
   */
  private $action;

  /**
   * @var \Date
   * @ORM\Column(name="date", type="date")    
   */
  private $date;

  /**
  * @ORM\ManyToOne(targetEntity="Customer", inversedBy="actions")
  * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", onDelete="CASCADE")
  */
  private $customer;

  /**
  * @ORM\ManyToOne(targetEntity="Box", inversedBy="actions")
  * @ORM\JoinColumn(name="box_id", referencedColumnName="id", onDelete="CASCADE")
  */
  private $box;

  /**
   * Get id
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set date
   * @param \Date $date
   * @return History
   */
  public function setDate($date) {
    $this->date = $date;
    return $this;
  }

  /**
   * Get date
   * @return \Date 
   */
  public function getDate() {
    return $this->date;
  }

  /**
   * Set action
   * @param integer $action
   * @return History
   */
  public function setAction($action) {
    $this->action = $action;
    return $this;
  }

  /**
   * Get action
   * @return integer 
   */
  public function getAction() {
    return $this->action;
  }

  /**
   * Set customer
   * @param \InventoryBundle\Entity\Customer $customer
   * @return Action
   */
  public function setCustomer(\InventoryBundle\Entity\Customer $customer = null) {
    $this->customer = $customer;
    return $this;
  }

  /**
   * Get customer
   * @return \InventoryBundle\Entity\Customer 
   */
  public function getCustomer() {
    return $this->customer;
  }

  /**
   * Set box
   * @param \InventoryBundle\Entity\Box $box
   * @return Action
   */
  public function setBox(\InventoryBundle\Entity\Box $box = null) {
    $this->box = $box;
    return $this;
  }

  /**
   * Get box
   * @return \InventoryBundle\Entity\Box 
   */
  public function getBox() {
    return $this->box;
  }

}
