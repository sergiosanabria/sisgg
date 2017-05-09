<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TasaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, array( 'attr'=>array('placeholder'=>'Ingrese el nombre')))
             ->add('abrev', null, array('label'=>'Abreviatura','attr'=>array('placeholder'=>'Ingrese una abreviatura')))
            ->add('descripcion', 'textarea', array('required'=>false, 'label'=>'Descripción','attr'=>array('placeholder'=>'Ingrese una descripcion')))
            ->add('valor', 'integer', array( 'attr'=>array('placeholder'=>'##.##'), 
                'invalid_message' => 'Debe ingresar un valor numerico.','required'=>true
                ))
            
            ->add('um', null,array('label'=>'Unidad de medida','attr'=>array('style'=>"display: none;")))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Tasa'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_tasatype';
    }
}
