<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemDescuentoVentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descuento')
            ->add('totalDescuento')
            ->add('totalDescuentoSinIva')
            ->add('porcentaje')
            ->add('detalle')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\ItemDescuentoVenta'
        ));
    }

    public function getName()
    {
        return 'itemdescuentoventatype';
    }
}
