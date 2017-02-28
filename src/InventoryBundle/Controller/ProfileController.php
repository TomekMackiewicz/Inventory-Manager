<?php

namespace InventoryBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use InventoryBundle\Entity\Customer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Controller managing the user profile.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends Controller {

  /**
   * Show the user.
   * @Route("/profile/")
   * @Method({"GET", "POST"})
   */
  public function showAction(Request $request) {

    $user = $this->getUser();
    if (!is_object($user) || !$user instanceof UserInterface) {
      throw new AccessDeniedException('This user does not have access to this section.');
    }

    $actionsFromTo = null;
    $datesError = null;
    $result = null;

    $em = $this->getDoctrine()->getManager(); 
    $customer = $em->getRepository('InventoryBundle:Customer')->find($user->getId());    
    $repo = $this->getDoctrine()->getRepository('InventoryBundle:Action');
    $boxesIn  = $em->getRepository('InventoryBundle:Customer')->boxesInCountByCustomer($user->getId());
    $boxesOut = $em->getRepository('InventoryBundle:Customer')->boxesOutCountByCustomer($user->getId());
    $actionsForm = $this->createForm('InventoryBundle\Form\ActionType');
    $actionsForm->handleRequest($request);

    $queryBuilder = $em->getRepository('InventoryBundle:Box')->createQueryBuilder('e');

    $filterForm = $this->createForm('InventoryBundle\Form\BoxFilterType');
    $filterForm->handleRequest($request);  

    if ($actionsForm->isSubmitted() && $actionsForm->isValid()) {
      $dateFrom = $actionsForm["dateFrom"]->getData()->format('Y-m-d');
      $dateTo = $actionsForm["dateTo"]->getData()->format('Y-m-d');
      if( strtotime($dateFrom) < strtotime($dateTo) ) {
        $actionsFromTo = $em->getRepository('InventoryBundle:Action')
          ->customerActionsFromTo($customer->getId(), $dateFrom, $dateTo);
      } else {
          $datesError = "Start value can't be higher than end date!";
      }
    }
 
    if ($filterForm->isSubmitted() && $filterForm->isValid()) {
      $queryBuilder = $this
        ->get('petkopara_multi_search.builder')
        ->searchForm($queryBuilder, $filterForm->get('search'));
        // ->andWhere(
        //     $queryBuilder->expr()->like('e.customer_id', ':customer_id')
        //   )
        // ->setParameter('customer_id', '%7%');        
      $query = $queryBuilder->getQuery();
      $result = $query->setMaxResults(100)->getResult();
    } 

    return $this->render('@FOSUser/Profile/show.html.twig', array(
      'user' => $user,
      'customer' => $customer,
      'boxesIn' =>$boxesIn,
      'boxesOut' =>$boxesOut,
      'actionsForm' => $actionsForm->createView(),
      'actionsFromTo' => $actionsFromTo,
      'datesError' => $datesError,      
      'filterForm' => $filterForm->createView(),
      'searchResults' => $result                
    ));
  }

  /**
   * Edit the user.
   *
   * @param Request $request
   *
   * @return Response
   */
  public function editAction(Request $request) {
    $user = $this->getUser();
    if (!is_object($user) || !$user instanceof UserInterface) {
      throw new AccessDeniedException('This user does not have access to this section.');
    }

    /** @var $dispatcher EventDispatcherInterface */
    $dispatcher = $this->get('event_dispatcher');

    $event = new GetResponseUserEvent($user, $request);
    $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

    if (null !== $event->getResponse()) {
      return $event->getResponse();
    }

    /** @var $formFactory FactoryInterface */
    $formFactory = $this->get('fos_user.profile.form.factory');

    $form = $formFactory->createForm();
    $form->setData($user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var $userManager UserManagerInterface */
      $userManager = $this->get('fos_user.user_manager');

      $event = new FormEvent($form, $request);
      $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

      $userManager->updateUser($user);

      if (null === $response = $event->getResponse()) {
        $url = $this->generateUrl('fos_user_profile_show');
        $response = new RedirectResponse($url);
      }

      $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

      return $response;
    }

    return $this->render('@FOSUser/Profile/edit.html.twig', array(
      'form' => $form->createView(),
    ));
  }
}