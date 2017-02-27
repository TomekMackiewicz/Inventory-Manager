<?php

namespace InventoryBundle\Controller;

use InventoryBundle\Entity\Box;
use InventoryBundle\Entity\Action;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Box controller.
 * @Route("box")
 */
class BoxController extends Controller {
  
  /**
   * Lists all box entities.
   * @Route("/", name="box_index")
   * @Method({"GET", "POST"})
   * @Template("box/index.html.twig")
   */
  public function indexAction(Request $request) {      
    $search = $request->get('search');
    $em = $this->getDoctrine()->getManager();
    $queryBuilder = $em->getRepository('InventoryBundle:Box')->createQueryBuilder('e');
    $filterForm = $this->createForm('InventoryBundle\Form\BoxFilterType');
    $filterForm->handleRequest($request);

    if ($filterForm->isValid()) {
      $queryBuilder = $this
        ->get('petkopara_multi_search.builder')
        ->searchForm($queryBuilder, $filterForm->get('search'));
      $query = $queryBuilder->getQuery();
      $result = $query->setMaxResults(100)->getResult();

      return [
        'filterForm' => $filterForm->createView(),            
        'searchResults' => $result
      ];
    }    
    return ['filterForm' => $filterForm->createView()];
  }

  /**
   * Creates a new box entity and new action.
   * @Route("/new", name="box_new")
   * @Method({"GET", "POST"})
   */
  public function newAction(Request $request) {
    $box = new Box();
    $action = new Action();
    $form = $this->createForm('InventoryBundle\Form\BoxType', $box);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $action->setBox($box);
      $action->setCustomer($box->getCustomer());
      $action->setDate($form['lastAction']->getData());
      $action->setAction($box->getStatus());
      $em->persist($box);
      $em->persist($action);
      $em->flush(); 

      return $this->redirectToRoute('box_show', array('id' => $box->getId()));
    }
    return $this->render('box/new.html.twig', array(
      'box' => $box,
      'form' => $form->createView(),
    ));
  }

  /**
   * Finds and displays a box entity.
   * @Route("/{id}", name="box_show")
   * @Method({"GET", "POST"})
   * @Template("box/show.html.twig")
   */
  public function showAction(Request $request, Box $box) {
    $actionsFromTo = null;
    $datesError = null;
    $em = $this->getDoctrine()->getManager();
    $repo = $this->getDoctrine()->getRepository('InventoryBundle:Action');
    $actionsForm = $this->createForm('InventoryBundle\Form\ActionType');
    $actionsForm->handleRequest($request);
    if ($actionsForm->isSubmitted() && $actionsForm->isValid()) {
      $dateFrom = $actionsForm["dateFrom"]->getData()->format('Y-m-d');
      $dateTo = $actionsForm["dateTo"]->getData()->format('Y-m-d');
      if( strtotime($dateFrom) < strtotime($dateTo) ) {
        $actionsFromTo = $em->getRepository('InventoryBundle:Action')
          ->boxActionsFromTo($box->getId(), $dateFrom, $dateTo);
      } else {
          $datesError = "Start value can't be higher than end date!";        
      }
    }
    return [
      'box' => $box,
      'actionsForm' => $actionsForm->createView(),
      'actionsFromTo' => $actionsFromTo,            
      'delete_form' => $this->createDeleteForm($box)->createView(),
      'datesError' => $datesError
    ];
  }

  /**
   * Displays a form to edit an existing box entity.
   * @Route("/{id}/edit", name="box_edit")
   * @Method({"GET", "POST"})
   */
  public function editAction(Request $request, Box $box) {
    $action = new Action();
    $editForm = $this->createForm('InventoryBundle\Form\BoxType', $box);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $action->setBox($box);
      $action->setCustomer($box->getCustomer());
      $action->setDate($editForm['lastAction']->getData());
      $action->setAction($box->getStatus());
      $em->persist($box);
      $em->persist($action);
      $em->flush();

      return $this->redirectToRoute('box_show', array('id' => $box->getId()));
    }
    return $this->render('box/edit.html.twig', array(
      'box' => $box,
      'edit_form' => $editForm->createView(),
      'delete_form' => $this->createDeleteForm($box)->createView()
    ));
  }

  /**
   * Deletes a box entity.
   * @Route("/{id}", name="box_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, Box $box) {
    $form = $this->createDeleteForm($box);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($box);
      $em->flush($box);
    }
    return $this->redirectToRoute('box_index');
  }

  /**
   * Creates a form to delete a box entity.
   * @param Box $box The box entity
   * @return \Symfony\Component\Form\Form The form
   */
  private function createDeleteForm(Box $box) {
    return $this->createFormBuilder()
      ->setAction($this->generateUrl('box_delete', array('id' => $box->getId())))
      ->setMethod('DELETE')
      ->getForm();
  }

}
