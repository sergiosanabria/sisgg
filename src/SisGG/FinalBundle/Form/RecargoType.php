<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class RecargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('iva',null,array('label'=>"I.V.A.",'empty_value'=>'Seleccione una Tasa','required'=>true))
            ->add('porcentaje')
            ->add('importe')
            ->add('tipoPorcentaje','radio',array('required'=>false,'label'=>'Recargo porcentual'))
            ->add('tipoImporte','radio',array('required'=>false,'label'=>'Recargo importe'))
            ->add('bonificacionImporte',null,array('required'=>false,'label'=>'Importe de Bonificación'))
            ->add('descripcion','textarea',array('required'=>false))
            ->add('tiposPedidos',null,array('label'=>"Aplicado a","multiple"=>true,"expanded"=>true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Recargo'
        ));
    }

    public function getName()
    {
        return 'recargotype';
    }
}
