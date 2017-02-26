<?php

namespace InventoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ActionType extends AbstractType {
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('dateFrom', 'date', array(
        'label' => 'From',
        'widget' => 'single_text',
        'attr'=> array('class'=>'datepicker')                
      ))
      ->add('dateTo', 'date', array(
        'label' => 'To',
        'widget' => 'single_text',
        'attr'=> array('class'=>'datepicker')                
      ));            
  }

}

