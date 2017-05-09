<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnidadMedidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, array( 'attr'=>array('placeholder'=>'Ingrese el nombre')))
            ->add('descripcion', 'textarea', array('required'=>false, 'label'=>'Descripción','attr'=>array('placeholder'=>'Ingrese una descripcion')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\UnidadMedida'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_unidadmedidatype';
    }
}
