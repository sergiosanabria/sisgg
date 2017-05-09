<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CierreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha',null,array('required'=>true,'widget'=>"single_text",'format' => 'dd/MM/yyyy'))    
            ->add('aclaracion','textarea',array('label'=>'Aclaración de Cierre','required'=>false))    
            ->add('itemCierre','collection',array('type'=>new ItemCierreType(),'allow_add'=>true,'allow_delete'=>true))    
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Cierre'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_cierretype';
    }
}
