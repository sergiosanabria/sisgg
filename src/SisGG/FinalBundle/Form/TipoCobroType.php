<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TipoCobroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion','textarea',array('required'=>false))
            ->add('liquido',null,array('label'=>'Registrable'))
            ->add('darCambio',null,array('label'=>'Dar Cambio'))
            ->add('montoMinimo',null,array('label'=>'Monto Minimo'))
            ->add('montoMaximo',null,array('label'=>'Monto Maximo'))
            ->add('campos','collection',array('allow_add'=>true,'allow_delete'=>true,'type'=>new CampoType()))    
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\TipoCobro'
        ));
    }

    public function getName()
    {
        return 'tipocobrotype';
    }
}
