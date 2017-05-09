<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DescuentoClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('porcentaje')
            ->add('minimo',null,array('required'=>false,'label'=>'Monto Mínimo'))
            ->add('maximo',null,array('required'=>false,'label'=>'Monto Máximo'))
            ->add('descripcion','textarea',array('required'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\DescuentoCliente'
        ));
    }

    public function getName()
    {
        return 'descuentoclientetype';
    }
}
