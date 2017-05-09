<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role',null,array('label'=>'Nombre del Rol'))
            ->add('permisos','collection',array('type'=>new PermisoType(),'allow_add'=>true,'allow_delete'=>true))    
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Rol'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_roltype';
    }
}
