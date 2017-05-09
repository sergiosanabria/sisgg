<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DescuentoProductoVentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('porcentaje',null,array('required'=>true))
            ->add('descripcion','textarea',array('required'=>false))
            ->add('productoVenta',null,array('required'=>false,'label'=>'Productos afectados'))
            ->add('categoriaProductoVenta',null,array('required'=>false,'label'=>'Categorias afectadas'))    
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\DescuentoProductoVenta'
        ));
    }

    public function getName()
    {
        return 'descuentoproductoventatype';
    }
}
