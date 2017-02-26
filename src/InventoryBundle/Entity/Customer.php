<?php

namespace InventoryBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * Customer
 *
 * @ORM\Table(name="customers")
 * @ORM\Entity(repositoryClass="InventoryBundle\Repository\CustomerRepository")
 * @UniqueEntity("name")
 */
class Customer extends BaseUser implements ParticipantInterface {

  /**
  * @ORM\Id
  * @ORM\Column(type="integer")
  * @ORM\GeneratedValue(strategy="AUTO")
  */
  protected $id;

  /**
   * @var string
   * @ORM\Column(name="name", type="string", length=64)
   * @Assert\NotBlank(
   *  message = "Name field cannot be empty."
   * )     
   */
  private $name;

  /**
   * @var string
   * @ORM\Column(name="address", type="string", length=255)
   * @Assert\NotBlank(
   *  message = "Address field cannot be empty."
   * )       
   */
  private $address;

  /**
  * @ORM\OneToMany(targetEntity="Box", mappedBy="customer")
  */
  private $boxes;

  /**
  * @ORM\OneToMany(targetEntity="Action", mappedBy="customer")
  */
  private $actions;

  /**
  * @ORM\OneToOne(targetEntity="Fees", mappedBy="customer")
  */
  private $fee;

  public function __construct() {
    parent::__construct();
    $this->boxes = new ArrayCollection();
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
   * Set name
   * @param string $name
   * @return Customer
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Get name
   * @return string 
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set address
   * @param string $address
   * @return Customer
   */
  public function setAddress($address) {
    $this->address = $address;
    return $this;
  }

  /**
   * Get address
   * @return string 
   */
  public function getAddress() {
    return $this->address;
  }

  /**
   * Add boxes
   * @param \InventoryBundle\Entity\Box $boxes
   * @return Customer
   */
  public function addBox(\InventoryBundle\Entity\Box $boxes) {
    $this->boxes[] = $boxes;
    return $this;
  }

  /**
   * Remove boxes
   * @param \InventoryBundle\Entity\Box $boxes
   */
  public function removeBox(\InventoryBundle\Entity\Box $boxes) {
    $this->boxes->removeElement($boxes);
  }

  /**
   * Get boxes
   * @return \Doctrine\Common\Collections\Collection 
   */
  public function getBoxes() {
    return $this->boxes;
  }

  /**
   * Add actions
   * @param \InventoryBundle\Entity\Action $actions
   * @return Customer
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

  /**
   * Set fee
   * @param \InventoryBundle\Entity\Fees $fee
   * @return Customer
   */
  public function setFee(\InventoryBundle\Entity\Fees $fee = null) {
    $this->fee = $fee;
    return $this;
  }

  /**
   * Get fee
   * @return \InventoryBundle\Entity\Fees 
   */
  public function getFee() {
    return $this->fee;
  }
  
}
