<?php

namespace InventoryBundle\Controller;

use InventoryBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Customer controller.
 * @Route("customer")
 */
class CustomerController extends Controller {
  
  /**
   * Lists all customer entities.
   * @Route("/", name="customer_index")
   * @Method("GET")
   */
  public function indexAction() {
    $em = $this->getDoctrine()->getManager();
    $customers = $em->getRepository('InventoryBundle:Customer')->excludeAdmin("ROLE_ADMIN");

    return $this->render('customer/index.html.twig', array(
      'customers' => $customers,
    ));
  }

  /**
   * Creates a new customer entity.
   * @Route("/new", name="customer_new")
   * @Method({"GET", "POST"})
   */
  public function newAction(Request $request) {
    $customer = new Customer();
    $form = $this->createForm('InventoryBundle\Form\CustomerType', $customer);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($customer);
      $em->flush($customer);

      return $this->redirectToRoute('customer_show', array('id' => $customer->getId()));
    }
    return $this->render('customer/new.html.twig', array(
      'customer' => $customer,
      'form' => $form->createView(),
    ));
  }

  /**
   * Finds and displays a customer entity.
   * @Route("/{id}", name="customer_show")
   * @Method({"GET", "POST"})
   * @Template("customer/show.html.twig")
   */
  public function showAction(Request $request, Customer $customer) {
    $actionsFromTo = null;
    $em = $this->getDoctrine()->getManager(); 
    $repo = $this->getDoctrine()->getRepository('InventoryBundle:Action');
    $boxesIn  = $em->getRepository('InventoryBundle:Customer')->boxesInCountByCustomer($customer->getId());
    $boxesOut = $em->getRepository('InventoryBundle:Customer')->boxesOutCountByCustomer($customer->getId());
    $actionsForm = $this->createForm('InventoryBundle\Form\ActionType');
    $actionsForm->handleRequest($request);
    if ($actionsForm->isSubmitted() && $actionsForm->isValid()) {
      $dateFrom = $actionsForm["dateFrom"]->getData()->format('Y-m-d');
      $dateTo = $actionsForm["dateTo"]->getData()->format('Y-m-d');
      $actionsFromTo = $em->getRepository('InventoryBundle:Action')
        ->customerActionsFromTo($customer->getId(), $dateFrom, $dateTo);
    }
    return [
      'customer' => $customer,
      'delete_form' => $this->createDeleteForm($customer)->createView(),
      'boxesIn' =>$boxesIn,
      'boxesOut' =>$boxesOut,
      'actionsForm' => $actionsForm->createView(),
      'actionsFromTo' => $actionsFromTo
    ];
  }

  /**
   * Displays a form to edit an existing customer entity.
   * @Route("/{id}/edit", name="customer_edit")
   * @Method({"GET", "POST"})
   */
  public function editAction(Request $request, Customer $customer) {
    $deleteForm = $this->createDeleteForm($customer);
    $editForm = $this->createForm('InventoryBundle\Form\CustomerType', $customer);
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {
      $this->getDoctrine()->getManager()->flush();
      return $this->redirectToRoute('customer_show', array('id' => $customer->getId()));
    }
    return $this->render('customer/edit.html.twig', array(
      'customer' => $customer,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView()
    ));
  }

  /**
   * Deletes a customer entity.
   * @Route("/{id}", name="customer_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, Customer $customer) {
    $form = $this->createDeleteForm($customer);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($customer);
      $em->flush($customer);
    }
    return $this->redirectToRoute('customer_index');
  }

  /**
   * Creates a form to delete a customer entity.
   * @param Customer $customer The customer entity
   * @return \Symfony\Component\Form\Form The form
   */
  private function createDeleteForm(Customer $customer) {
    return $this->createFormBuilder()
      ->setAction($this->generateUrl('customer_delete', array('id' => $customer->getId())))
      ->setMethod('DELETE')
      ->getForm();
  }

}