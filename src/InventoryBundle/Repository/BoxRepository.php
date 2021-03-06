<?php

namespace InventoryBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BoxRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BoxRepository extends EntityRepository {

	public function boxesIn() {
		$boxesIn = $this->getEntityManager()->createQuery(
			"SELECT count(b.id) FROM InventoryBundle:Box b WHERE b.status='In'"
		)->getSingleScalarResult();
		return $boxesIn;
	}

	public function boxesOut() {
		$boxesOut = $this->getEntityManager()->createQuery(
			"SELECT count(b.id) FROM InventoryBundle:Box b WHERE b.status='Out'"
		)->getSingleScalarResult();
		return $boxesOut;
	}

}
