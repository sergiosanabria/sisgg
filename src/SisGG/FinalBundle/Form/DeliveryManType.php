<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DeliveryManType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni')
            ->add('apellido')
            ->add('nombre')
            ->add('direccion',new DireccionType())
            ->add('username',null,array('label'=>'Nombre de Usuario'))
            ->add('password', 'repeated', array('type' => 'password',
                'first_name' => 'Nueva contraseña',
                'second_name' => 'Confirmar contraseña',
                'invalid_message' => 'Los campos de contraseña deben coincidir.',
                'options' => array('label' => 'Contraseña','attr'=>array('class'=>'span3')),
                ))
            ->add('email')
            ->add('role',null,array('label'=>'Rol'))
            ->add('email')
            ->add('role')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\DeliveryMan'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_deliverymantype';
    }
}
