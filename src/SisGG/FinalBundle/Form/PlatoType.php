<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlatoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion', 'textarea',array('required'=>false, 'label'=>'Descripción'))
            ->add('precioCosto', 'money', array( 'attr'=>array('placeholder'=>'##.##', 'onkeypress'=>" return isNumberKey(this,event)", 'onkeyup'=>'aumentarMargen(0)' ), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false, 'label'=>'Precio de costo'
                ))
            ->add('margen', 'number', array( 'attr'=>array('placeholder'=>'##.##',  'onkeypress'=>" return isNumberKey(this,event);", 'onkeyup'=>'aumentarMargen(1)' ),
                'invalid_message' => 'Debe ingresar datos numéricos','required'=>false
                ))
               ->add('margenMin', 'number',  array( 'attr'=>array('placeholder'=>'##.##' ,'tipo'=>'' ,'onkeyup'=>'margenMax(this);'), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false, 'label'=>'Margen mínimo'
                ))
            ->add('precioVenta', 'money', array( 'attr'=>array('placeholder'=>'##.##', 'onkeypress'=>" return isNumberKey(this,event)",'onkeyup'=> 'aumentarMargen(2)'), 
                'invalid_message' => 'Debe ingresar datos numéricos.', 'required'=>false,'label'=>'Precio de venta'
                ))
            
            ->add('cantidad', 'number', array( 'attr'=>array('placeholder'=>'##.##', 'onkeypress'=>" return isNumberKey(this,event)"), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false
                ))
            ->add('iva', 'entity', array('class' => "SisGG\FinalBundle\Entity\IVA", 'empty_value' => 'Seleccione una tasa de IVA.', 'label'=>'IVA', 'required'=>true,'query_builder' => function ( $repository) {
                            return $repository->createQueryBuilder('i')->where("i.activo=1");
                        }))
            ->add('cantidadMinima', 'number', array( 'attr'=>array('placeholder'=>'##', 'onkeypress'=>" return isNumberKey(this,event)"), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false,'label'=>'Cantidad mínima'
                ))
            ->add('categoria', 'entity', array('class' => "SisGG\FinalBundle\Entity\CategoriaProductoVenta", 'empty_value' => 'Seleccione una categoria', 'required'=>true, 'query_builder' => function ($repository) {
                            return $repository->createQueryBuilder('p')->where("p.activo=1");
                        }))
            ->add('tasa', 'entity', array('class' => "SisGG\FinalBundle\Entity\Tasa", 'empty_value' => false, 'required'=>true, 'query_builder' => function ($repository) {
                            return $repository->createQueryBuilder('p');
                        })) 
            ->add('foto',new ImageType())
                                ->add('conIng', 'choice', array( 'attr'=>array('class'=>'span8'),'label'=>'Cálculo de precio de costo' , 'choices' => array(true => 'Sumando el costo de los ingredientes', false => 'El precio fijado ahora')))          
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Plato'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_platotype';
    }
}
