<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['attr']['tipo']==1){
            $builder
            ->add('apellido')
            ->add('nombre')
            ->add('email')   
            ->add('username',null,array('label'=>'Nombre de Usuario'))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('attr'=>array('tipo'=>'direccion'),'label' => 'Contraseña'),
                'second_options' => array('attr'=>array('tipo'=>'direccion'),'label' => 'Repita contraseña')))
            ->add('role', 'entity', array('label'=>'Rol','class' => "SisGG\FinalBundle\Entity\Rol", 'query_builder' => function ($repository) {
                        return $repository->createQueryBuilder('p')->where('p.activo=1');
                    })) ;
        }elseif ($options['attr']['tipo']==0){
             $builder
            ->add('apellido')
            ->add('nombre')
            ->add('email')   
            ->add('username',null,array('label'=>'Nombre de Usuario'))
            ->add('role', 'entity', array('label'=>'Rol','class' => "SisGG\FinalBundle\Entity\Rol", 'query_builder' => function ($repository) {
                        return $repository->createQueryBuilder('p');
                    })) ;
        }
        elseif ($options['attr']['tipo']==2){
             
            $builder->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('attr'=>array('tipo'=>'direccion'),'label' => 'Contraseña'),
                'second_options' => array('attr'=>array('tipo'=>'direccion'),'label' => 'Repita contraseña')));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Usuario'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_usuariotype';
    }
}
