<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RedireccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url')
            ->add('usuarioA', 'entity', array(
                    'label' => 'Redireccionar a',
                    'required' => true,
                    'property' => 'apellidoYNombre',
                    'class' => 'SisGGFinalBundle:Usuario',
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Redireccion'
        ));
    }

    public function getName()
    {
        return 'redirecciontype';
    }
}
