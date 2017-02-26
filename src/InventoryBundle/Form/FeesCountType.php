<?php

namespace InventoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FeesCountType extends AbstractType {
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder 
      ->add('dateFrom', DateType::class, array(
        'label' => 'From',
        'widget' => 'single_text',
        'attr'=> array('class'=>'datepicker')
      ))
      ->add('dateTo', DateType::class, array(
        'label' => 'To',
        'widget' => 'single_text',
        'attr'=> array('class'=>'datepicker')
      ));                        
  }

}