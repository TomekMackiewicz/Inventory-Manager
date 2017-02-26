<?php

namespace InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fees
 * @ORM\Table(name="fees")
 * @ORM\Entity(repositoryClass="InventoryBundle\Repository\FeesRepository")
 */
class Fees {
  /**
   * @var int
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var int
   * @ORM\Column(name="delivery", type="integer")
   */
  private $delivery;

  /**
   * @var int
   * @ORM\Column(name="import", type="integer")
   */
  private $import;

  /**
   * @var int
   * @ORM\Column(name="storage", type="integer")
   */
  private $storage;

  /**
  * @ORM\OneToOne(targetEntity="Customer", inversedBy="fee")
  * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", onDelete="CASCADE")
  */
  private $customer;

  /**
   * Get id
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set delivery
   * @param integer $delivery
   * @return Fees
   */
  public function setDelivery($delivery) {
    $this->delivery = $delivery;
    return $this;
  }

  /**
   * Get delivery
   * @return integer 
   */
  public function getDelivery() {
    return $this->delivery;
  }

  /**
   * Set import
   * @param integer $import
   * @return Fees
   */
  public function setImport($import) {
    $this->import = $import;
    return $this;
  }

  /**
   * Get import
   * @return integer 
   */
  public function getImport() {
    return $this->import;
  }

  /**
   * Set storage
   * @param integer $storage
   * @return Fees
   */
  public function setStorage($storage) {
    $this->storage = $storage;
    return $this;
  }

  /**
   * Get storage
   * @return integer 
   */
  public function getStorage() {
    return $this->storage;
  }

  /**
   * Set customer
   * @param \InventoryBundle\Entity\Customer $customer
   * @return Fees
   */
  public function setCustomer(\InventoryBundle\Entity\Customer $customer = null){
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
  
}
