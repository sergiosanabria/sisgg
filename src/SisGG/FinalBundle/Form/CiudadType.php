<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SisGG\FinalBundle\Form\ProvinciaType;

class CiudadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, array('attr'=>array('placeholder'=>'Ingrese el nombre'), 'required'=>true))
            ->add('codigoPostal', 'integer', array(
                'invalid_message' => 'Debe ser un número de cuatro dígitos',
                'max_length' => 4,
               'label' => 'Código Postal','attr'=>array('class'=>'input-mini','placeholder'=>'####')
                    ))
             ->add('provincia', 'entity', array('class' => "SisGG\FinalBundle\Entity\Provincia", 'query_builder' => function ($repository) {
                                return $repository->createQueryBuilder('p');
                            }))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Ciudad'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_ciudadtype';
    }
}
