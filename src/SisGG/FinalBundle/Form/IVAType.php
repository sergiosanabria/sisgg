<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IVAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                
        ->add('tasa', 'number',  array( 'attr'=>array('placeholder'=>'##.##' ), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>true
                ))
        ->add('descripcion', 'textarea',array('attr'=>array('placeholder'=>'Ingrese una descripción...'),'required'=>false, 'label'=>'Descripción'))        
        ->add('gravado', 'choice', array('choices' => array(true => 'Si', false => 'No')
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\IVA'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_ivatype';
    }
}
