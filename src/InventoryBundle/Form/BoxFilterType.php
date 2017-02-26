<?php

namespace InventoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BoxFilterType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('search', MultiSearchType::class, array(
        'label' => 'Search by signature, status or date (use * as wildcard)',
        'class' => 'InventoryBundle:Box',
        'search_comparison_type' => 'equals' 
        //optional, what type of comparison to applied ('wildcard','starts_with', 'ends_with', 'equals')        
      ));
  }    
}

