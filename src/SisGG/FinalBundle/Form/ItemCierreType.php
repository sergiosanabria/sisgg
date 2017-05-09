<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemCierreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoCobro')
            ->add('importeSistema','hidden')
            ->add('importeReal',null,array('attr'=>array('class'=>'span12')))    
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\ItemCierre'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_itemcierretype';
    }
}
