<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SisGG\FinalBundle\Form\EventListener\AddProvinciaSubscriber;
class LocalidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new AddProvinciaSubscriber($builder->getFormFactory()));
        $builder
            ->add('nombre')            
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Localidad'
        ));
    }

    public function getName()
    {
        return 'localidad';
    }
}
