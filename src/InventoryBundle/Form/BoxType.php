<?php

namespace InventoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;

class BoxType extends AbstractType {
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('signature')
      ->add('status', ChoiceType::class, [
        'choices'  => [
          'In' => 'In',
          'Out' => 'Out'
        ]
      ])
      ->add('lastAction', DateType::class, [
        'label' => 'Date',
        'widget' => 'single_text',
        'attr'=> ['class'=>'datepicker']
      ])
      ->add('customer', EntityType::class,[
        'class' => 'InventoryBundle:Customer',
        'choice_label' => 'name',
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->where('c.roles NOT LIKE :roles')
            ->setParameter('roles', '%ROLE_ADMIN%');
        }
      ]);
  }
  
  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => 'InventoryBundle\Entity\Box'
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix() {
    return 'inventorybundle_box';
  }

}
