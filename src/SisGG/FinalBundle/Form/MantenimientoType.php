<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MantenimientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('nombre', null, array('attr' => array('placeholder' => 'Ingrese el nombre')))
                ->add('descripcion', 'textarea', array('attr' => array('placeholder' => 'Ingrese una descripción...'), 'required' => false, 'label' => 'Descripción'))
                ->add('precioCosto', 'money', array('attr' => array('placeholder' => '##.##'),
                    'invalid_message' => 'Debe ingresar datos numéricos.', 'required' => false, 'label' => 'Precio de costo'
                ))
                ->add('iva', 'entity', array('class' => "SisGG\FinalBundle\Entity\IVA", 'empty_value' => 'Seleccione una tasa de IVA.', 'label'=>'IVA', 'required'=>true,'query_builder' => function ( $repository) {
                            return $repository->createQueryBuilder('i')->where("i.activo=1");
                        }))
                ->add('tasa')
                ->add('proveedor')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Mantenimiento'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_mantenimientotype';
    }
}
