<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DireccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudad',null, array('required'=>true))
            ->add('calle',null,array('required'=>true, 'attr'=>array('tipo'=>'direccion')))
            ->add('numero','text',array('max_length' => 4,'required'=>true, 'attr'=>array('tipo'=>'direccion')))
            ->add('manzana',null,array('required'=>false, 'attr'=>array('tipo'=>'direccion')))
            ->add('edificio',null,array('required'=>false, 'attr'=>array('tipo'=>'direccion')))
            ->add('escalera',null,array('required'=>false, 'attr'=>array('tipo'=>'direccion')))
            ->add('piso',null,array('required'=>false, 'attr'=>array('tipo'=>'direccion')))
            ->add('departamento',null,array('required'=>false, 'attr'=>array('tipo'=>'direccion')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Direccion'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_direcciontype';
    }
}
