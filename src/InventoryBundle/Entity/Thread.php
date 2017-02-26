<?php

namespace InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity
 */
class Thread extends BaseThread {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\ManyToOne(targetEntity="InventoryBundle\Entity\Customer")
   * @var \FOS\MessageBundle\Model\ParticipantInterface
   */
  protected $createdBy;

  /**
   * @ORM\OneToMany(
   *   targetEntity="InventoryBundle\Entity\Message",
   *   mappedBy="thread"
   * )
   * @var Message[]|Collection
   */
  protected $messages;

  /**
   * @ORM\OneToMany(
   *   targetEntity="InventoryBundle\Entity\ThreadMetadata",
   *   mappedBy="thread",
   *   cascade={"all"}
   * )
   * @var ThreadMetadata[]|Collection
   */
  protected $metadata;
    
}