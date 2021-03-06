<?php

namespace InventoryBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ActionRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActionRepository extends EntityRepository {

	public function lastActions() {
		$lastActions = $this->getEntityManager()->createQuery(
			"SELECT action 
			 FROM InventoryBundle:Action action 
			 ORDER BY action.date DESC"
		)->setMaxResults(5)->getResult();
		return $lastActions;		
	}

	public function customerActionsFromTo($id, $from, $to) {
		$actionsFromTo = $this->getEntityManager()->createQuery(
			"SELECT a 
			 FROM InventoryBundle:Action a 
			 WHERE a.customer = '$id' 
			 AND a.date BETWEEN '$from' and '$to' 
			 ORDER BY a.date DESC"
		)->getResult();
		return $actionsFromTo;
	}

	public function boxActionsFromTo($id, $from, $to) {
		$actionsFromTo = $this->getEntityManager()->createQuery(
			"SELECT a FROM InventoryBundle:Action a 
			 WHERE a.box = '$id' 
			 AND a.date BETWEEN '$from' and '$to' 
			 ORDER BY a.date DESC"
		)->getResult();
		return $actionsFromTo;
	}

}
