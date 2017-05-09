<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PuntoVentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero',null,array('attr'=>array('tipo'=>'spinner','min'=>'1','max'=>'9998')))
            ->add('numerofacturaBase',null,array('label'=>'Numero de Factura Base','attr'=>array('min'=>'1','max'=>'99999999','title'=>'Numero de 8 digitos.Ej:00000001','tipo'=>'spinner')))    
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\PuntoVenta'
        ));
    }

    public function getName()
    {
        return 'puntoventatype';
    }
}
