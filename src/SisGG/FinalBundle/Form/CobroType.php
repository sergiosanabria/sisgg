<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CobroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoCobro',null,array('attr'=>array('onchange'=>'a(this);')))    
            ->add('importe',null,array('required'=>true))
            ->add('aclaracion',null,array('required'=>false))
            ->add('valores','collection',array('allow_add'=>true,'allow_delete'=>true,'type'=> new ValorType(),'attr'=>array('añadir'=>false),'prototype_name'=>'__valor__'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Cobro'
        ));
    }

    public function getName()
    {
        return 'cobrotype';
    }
}
