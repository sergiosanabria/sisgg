<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CajaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('puntoVenta',null,array('label'=>'Punto de Venta'))
            ->add('ultimaFactura',null,array('label'=>'Ultima Factura'))
            ->add('minimoApertura',null,array('label'=>'Importe sugerido de apertura'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Caja'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_cajatype';
    }
}
