<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReciboType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha',null,array('widget'=>"single_text",'format' => 'dd/MM/yyyy'))
            ->add('serie')
            ->add('numero')
            ->add('nombre',null,array('label'=>'Apellido y Nombre, Razón social'))
            ->add('total',null,array('label'=>'Total entregado'))
            ->add('cliente')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Recibo'
        ));
    }

    public function getName()
    {
        return 'recibotype';
    }
}
