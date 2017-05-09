<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AperturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha',null,array('required'=>true,'widget'=>"single_text",'format' => 'dd/MM/yyyy'))    
            ->add('importe',null,array('label'=>'Saldo al Inicio'))
            ->add('aclaracion','textarea',array('label'=>'Aclaración de Apertura','required'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Apertura'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_aperturatype';
    }
}
