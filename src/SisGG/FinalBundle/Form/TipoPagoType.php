<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TipoPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text', array( 'attr'=>array('placeholder'=>'Ingrese el nombre'))) 
            ->add('descripcion', 'textarea',array('attr'=>array('placeholder'=>'Ingrese una descripción...'),'required'=>false, 'label'=>'Descripción'))
            ->add('atr1','text', array( 'attr'=>array('placeholder'=>'Campo 1'), 'label'=>'Dato 1', 'required'=>true)) 
            ->add('tipo1','choice', array('label'=>'Tipo de dato','choices' => array( 0 => 'Numérico - No obligatorio', 1=>'Numérico - Obligatorio', 2=>'Alfa-Numérico - No obligatorio',  3=>'Alfa-Numérico - Obligatorio')))            
            ->add('atr2','text', array( 'attr'=>array('placeholder'=>'Campo 1'), 'label'=>'Dato 2')) 
            ->add('tipo2','choice', array('label'=>'Tipo de dato','choices' => array( 0 => 'Numérico - No obligatorio', 1=>'Numérico - Obligatorio', 2=>'Alfa-Numérico - No obligatorio',  3=>'Alfa-Numérico - Obligatorio')))
            ->add('atr3','text', array( 'attr'=>array('placeholder'=>'Campo 1'), 'label'=>'Dato 3')) 
            ->add('tipo3','choice', array('label'=>'Tipo de dato','choices' => array( 0 => 'Numérico - No obligatorio', 1=>'Numérico - Obligatorio', 2=>'Alfa-Numérico - No obligatorio',  3=>'Alfa-Numérico - Obligatorio')))
            ->add('atr4','text', array( 'attr'=>array('placeholder'=>'Campo 1'), 'label'=>'Dato 4')) 
            ->add('tipo4','choice', array('label'=>'Tipo de dato','choices' => array( 0 => 'Numérico - No obligatorio', 1=>'Numérico - Obligatorio', 2=>'Alfa-Numérico - No obligatorio',  3=>'Alfa-Numérico - Obligatorio')))
            ->add('atr5','text', array( 'attr'=>array('placeholder'=>'Campo 1'), 'label'=>'Dato 5')) 
            ->add('tipo5','choice', array('label'=>'Tipo de dato','choices' => array( 0 => 'Numérico - No obligatorio', 1=>'Numérico - Obligatorio', 2=>'Alfa-Numérico - No obligatorio',  3=>'Alfa-Numérico - Obligatorio')))
                
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\TipoPago'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_tipopagotype';
    }
}
