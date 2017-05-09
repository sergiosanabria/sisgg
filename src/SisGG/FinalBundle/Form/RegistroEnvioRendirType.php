<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistroEnvioRendirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleado',null,array('label'=>'Encargado del Envio','required'=>true))
            ->add('pedido')
            ->add('totalPedidos')
            ->add('totalRendido',null,array('label'=>'Total rendido','required'=>true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\RegistroEnvio'
        ));
    }

    public function getName()
    {
        return 'registroenviotype';
    }
}
