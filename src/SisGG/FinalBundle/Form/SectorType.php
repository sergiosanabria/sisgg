<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',null,array('required'=>true,'attr'=>array('placeholder'=>'Ingrese un nombre...')))
            ->add('mesas','collection',array('type'=>new MesaType(),'allow_add'=>true,'allow_delete'=>true,'by_reference'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Sector'
        ));
    }

    public function getName()
    {
        return 'sectortype';
    }
}
