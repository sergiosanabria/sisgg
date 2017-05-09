<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PagoType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hoy = new \DateTime('now');
        $hoy = date_format($hoy, 'd/m/Y');
        $builder
            ->add('tipoCobro',null,array('attr'=>array('onchange'=>'a(this);'))) 
            ->add('importe','number', array('attr'=>array('tipo'=>'', 'min'=>0),'required'=>true))
            ->add('aclaracion','text', array('required'=>false))
            ->add('fecha', 'date',array('format' => 'dd/MM/yyyy','widget'=>'single_text', 'attr'=>array('value'=>$hoy)))
            ->add('valores','collection',array('allow_add'=>true,'allow_delete'=>true,'type'=> new ValorType(),'attr'=>array('añadir'=>false),'prototype_name'=>'__valor__'))
            //->add('atr','text', array('required'=>true,'attr'=>array('style'=>'display:none',)))
            //->add('compra')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Pago'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_pagotype';
    }
}
