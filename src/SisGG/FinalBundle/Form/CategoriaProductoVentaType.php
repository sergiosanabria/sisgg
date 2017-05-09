<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoriaProductoVentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',null,array('required'=>true, 'attr'=>array('placeholder'=>'Ingrese el nombre')))
            ->add('descripcion', null,array('required'=>false, 'label'=>'Descripción', 'attr'=>array('placeholder'=>'Ingrese una descripción...')))
            ->add('padre')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\CategoriaProductoVenta'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_categoriaproductoventatype';
    }
}
