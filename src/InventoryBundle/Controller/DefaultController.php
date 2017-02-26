<?php

namespace InventoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InventoryBundle\Entity\Box;
use InventoryBundle\Entity\Action;

class DefaultController extends Controller {
  /**
   * @Route("main")
   * @Template("::index.html.twig")
   */
  public function indexAction() {
	  $em = $this->getDoctrine()->getManager();
    return [
    	'lastActions' => $em->getRepository('InventoryBundle:Action')->lastActions(),
    	'boxesIn'	=> $em->getRepository('InventoryBundle:Box')->boxesIn(),
    	'boxesOut' => $em->getRepository('InventoryBundle:Box')->boxesOut(),
    	'boxesInCount' => $em->getRepository('InventoryBundle:Customer')->boxesInCountByCustomers(),
    	'boxesOutCount' => $em->getRepository('InventoryBundle:Customer')->boxesOutCountByCustomers()
    ];
  }
}
