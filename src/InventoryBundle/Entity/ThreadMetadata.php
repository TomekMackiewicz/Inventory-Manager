<?php

namespace InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;

/**
 * @ORM\Entity
 */
class ThreadMetadata extends BaseThreadMetadata {

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\ManyToOne(
   *   targetEntity="InventoryBundle\Entity\Thread",
   *   inversedBy="metadata"
   * )
   * @var \FOS\MessageBundle\Model\ThreadInterface
   */
  protected $thread;

  /**
   * @ORM\ManyToOne(targetEntity="InventoryBundle\Entity\Customer")
   * @var \FOS\MessageBundle\Model\ParticipantInterface
   */
  protected $participant;

}