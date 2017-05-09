<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OperacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoOperacion',null,array('label'=>'Tipo de Operacion','required'=>true,'empty_value'=>"Seleccione un Tipo de Operación"))
            ->add('de',null,array('label'=>'De','required'=>true,'empty_value'=>"Seleccione una Condición"))
            ->add('a',null,array('label'=>'A','required'=>true,'empty_value'=>"Seleccione una Condición"))
            ->add('tipoFactura',null,array('label'=>'Tipo de Factura','required'=>true,'empty_value'=>"Seleccione un Tipo de Factura"))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Operacion'
        ));
    }

    public function getName()
    {
        return 'operaciontype';
    }
}
