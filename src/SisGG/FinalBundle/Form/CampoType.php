<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CampoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('tipoDato','choice', array('choices'=> array('text' => 'Cadena', 'number' => 'Numerico', 'date' => 'Fecha','checkbox'=>'Verdadero o Falso'),'required'  => true,'attr'=>array('class'=>'tipo_dato')))
            ->add('patron',null,array('label'=>'Formato','attr'=>array('class'=>'patron')))
            ->add('ejemplo',null,array('label'=>'Ejemplo de Patron','attr'=>array('placeholder'=>'Ejemplo...')))    
            ->add('requerido')
            
                
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Campo'
        ));
    }

    public function getName()
    {
        return 'campotype';
    }
}
