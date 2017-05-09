<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MozoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni')
            ->add('apellido')
            ->add('nombre')
            ->add('direccion',new DireccionType())                
            ->add('telefonos','collection')    
            ->add('username',null,array('label'=>'Nombre de Usuario'))
            ->add('password', 'repeated', array('type' => 'password',
                'invalid_message' => 'Los campos de contraseña deben coincidir.',
                'options' => array('label' => 'Contraseña'),
                ))
            ->add('email')
            ->add('role',null,array('label'=>'Rol'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Mozo'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_mozotype';
    }
}
