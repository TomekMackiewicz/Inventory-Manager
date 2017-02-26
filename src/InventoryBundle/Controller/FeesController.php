<?php

namespace InventoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use InventoryBundle\Entity\Fees;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Fees controller.
 * @Route("fees")
 */
class FeesController extends Controller {

  /**
   * Lists all fees.
   * @Route("/", name="fees_index")
   * @Method({"GET", "POST"})
   * @Template("fees/index.html.twig")
   */
  public function indexAction(Request $request) {
    $em = $this->getDoctrine()->getManager();
    $fees = $em->getRepository('InventoryBundle:Fees')->findAll();

    return $this->render('fees/index.html.twig', array(
      'fees' => $fees
    ));
  }

  /**
   * Creates a new fee entity.
   * @Route("/new", name="fees_new")
   * @Method({"GET", "POST"})
   */
  public function newAction(Request $request) {
    $fee = new Fees();
    $form = $this->createForm('InventoryBundle\Form\FeesType', $fee);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($fee);
      $em->flush($fee);

      return $this->redirectToRoute('fees_show', array('id' => $fee->getId()));
    }
    return $this->render('fees/new.html.twig', array(
      'fee' => $fee,
      'form' => $form->createView(),
    ));
  }

  /**
   * Finds and displays a fees entity.
   * @Route("/{id}", name="fees_show")
   * @Method({"GET", "POST"})
   * @Template("fees/show.html.twig")
   */
  public function showAction(Request $request, Fees $fee) {
    $feesTable = null;
    $datesError = null;
    $sum = null;
    $months = 0;
    $years = 0;
    $em = $this->getDoctrine()->getManager();
    $deleteForm = $this->createDeleteForm($fee); 
    $calculateFeesForm = $this->createForm('InventoryBundle\Form\FeesCountType');
    $calculateFeesForm->handleRequest($request);
    if ($calculateFeesForm->isSubmitted() && $calculateFeesForm->isValid()) {
      $dateFrom = $calculateFeesForm["dateFrom"]->getData()->format('Y-m-d');
      $dateTo = $calculateFeesForm["dateTo"]->getData()->format('Y-m-d');
      if( strtotime($dateFrom) < strtotime($dateTo) ) {
        $datesError = null;
        $start = (new \DateTime($dateFrom))->modify('first day of this month');
        $end = (new \DateTime($dateTo))->modify('first day of next month');
        $interval = $end->diff($start);
        $interval->format('%m months');
        $months = $interval->m;
        $years = $interval->y;
        $feesTable = $em->getRepository('InventoryBundle:Fees')
          ->actionsToCalculate($fee->getCustomer()->getId(), $dateFrom, $dateTo);
        $sum = ($feesTable['storage']*($interval->m + ($interval->y*12))) + 
          ($feesTable['import']*$feesTable['actionIn']) +
          ($feesTable['delivery']*$feesTable['actionOut']);
      } else {
        $datesError = "Start value can't be higher than end date!";              
      } 
    }

    return [
      'fee' => $fee,
      'delete_form' => $deleteForm->createView(),
      'calculateFeesForm' => $calculateFeesForm->createView(),
      'feesTable' => $feesTable,
      'sum' => $sum,
      'months' => $months,
      'years' => $years,
      'datesError' => $datesError
    ];
  }

  /**
   * Displays a form to edit an existing fees entity.
   * @Route("/{id}/edit", name="fees_edit")
   * @Method({"GET", "POST"})
   */
  public function editAction(Request $request, Fees $fees) {
    $deleteForm = $this->createDeleteForm($fees);
    $editForm = $this->createForm('InventoryBundle\Form\FeesType', $fees);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $this->getDoctrine()->getManager()->flush();
      return $this->redirectToRoute('fees_show', array('id' => $fees->getId()));
    }
    return $this->render('fees/edit.html.twig', array(
      'fees' => $fees,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView()
    ));
  }

  /**
   * Deletes a fee entity.
   * @Route("/{id}", name="fees_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, Fees $fees) {
    $form = $this->createDeleteForm($fees);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($fees);
      $em->flush($fees);
    }
    return $this->redirectToRoute('fees_index');
  }

  /**
   * Creates a form to delete a fee entity.
   * @param Fees $fees The fees entity
   * @return \Symfony\Component\Form\Form The form
   */
  private function createDeleteForm(Fees $fees)
  {
    return $this->createFormBuilder()
      ->setAction($this->generateUrl('fees_delete', array('id' => $fees->getId())))
      ->setMethod('DELETE')
      ->getForm();
  }

}