<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CocinaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',null)
            ->add('numeroTandas','integer',array('label'=>'Número de Tandas'))  
            ->add('marcasTemporales','collection',array('label'=>'Marcas de Tiempo','type'=>new MarcaTemporalType(),'allow_add'=>true,'allow_delete'=>true))    
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Cocina'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_cocinatype';
    }
}
