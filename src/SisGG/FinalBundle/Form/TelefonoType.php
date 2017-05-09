<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TelefonoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nacional',null,array('attr'=>array('class'=>'span12', 'value'=>'+54')))
            ->add('caracteristica',null,array('attr'=>array('class'=>'span12','placeholder'=>'376')))
            ->add('numero',null,array('attr'=>array('class'=>'span12','placeholder'=>'4375871')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Telefono'
        ));
    }

    public function getName()
    {
        return 'telefono';
    }
}
