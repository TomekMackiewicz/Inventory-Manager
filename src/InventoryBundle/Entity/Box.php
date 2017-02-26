<?php

namespace InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Box
 * @ORM\Table(name="boxes")
 * @ORM\Entity(repositoryClass="InventoryBundle\Repository\BoxRepository")
 * @UniqueEntity("signature")
 */
class Box {
  /**
  * @var int
  * @ORM\Column(name="id", type="integer")
  * @ORM\Id
  * @ORM\GeneratedValue(strategy="AUTO")
  */
  private $id;

  /**
  * @var string
  * @ORM\Column(name="signature", type="string", length=32)
  * @Assert\NotBlank(
  *  message = "Signature cannot be empty."
  * )    
  */
  private $signature;

  /**
  * @var int
  * @ORM\Column(name="status", type="string", length=3)
  * @Assert\NotBlank(
  *  message = "Status cannot be empty."
  * )
  * @Assert\Choice(
  * choices = { "In", "Out" },
  * message = "Choose a valid value (In or Out)."
  * )        
  */
  private $status;

  /**
  * @var \Date
  * @ORM\Column(name="last_action", type="date")
  * @Assert\NotBlank(
  *  message = "Date cannot be empty."
  * )  
  * @Assert\Date(
  *  message = "Date must have a correct format."
  * )
  */
  private $lastAction;

  /**
  * @ORM\ManyToOne(targetEntity="Customer", inversedBy="boxes")
  * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", onDelete="CASCADE")
  * @Assert\NotBlank(
  *  message = "Customer field cannot be empty."
  * )    
  */
  private $customer;

  /**
  * @ORM\OneToMany(targetEntity="Action", mappedBy="box")
  */
  private $actions;

  public function __construct() {
    $this->actions = new ArrayCollection();
  }

  /**
   * Get id
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set signature
   * @param string $signature
   * @return Box
   */
  public function setSignature($signature){
    $this->signature = $signature;
    return $this;
  }

  /**
   * Get signature
   * @return string 
   */
  public function getSignature() {
    return $this->signature;
  }

  /**
   * Set status
   * @param integer $status
   * @return Box
   */
  public function setStatus($status) {
    $this->status = $status;
    return $this;
  }

  /**
   * Get status
   * @return integer 
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * Set lastAction
   * @param \Date $lastAction
   * @return Box
   */
  public function setLastAction($lastAction) {
    $this->lastAction = $lastAction;
    return $this;
  }

  /**
   * Get lastAction
   * @return \Date 
   */
  public function getLastAction() {
    return $this->lastAction;
  }

  /**
   * Set customer
   * @param \InventoryBundle\Entity\Customer $customer
   * @return Box
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
   * Add actions
   * @param \InventoryBundle\Entity\Action $actions
   * @return Box
   */
  public function addAction(\InventoryBundle\Entity\Action $actions) {
    $this->actions[] = $actions;
    return $this;
  }

  /**
   * Remove actions
   * @param \InventoryBundle\Entity\Action $actions
   */
  public function removeAction(\InventoryBundle\Entity\Action $actions) {
    $this->actions->removeElement($actions);
  }

  /**
   * Get actions
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getActions() {
    return $this->actions;
  }

}
