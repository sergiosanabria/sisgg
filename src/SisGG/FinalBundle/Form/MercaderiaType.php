<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SisGG\FinalBundle\Form\ImageType;

class MercaderiaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('nombre',null, array( 'attr'=>array('placeholder'=>'Ingrese el nombre'))) 
            ->add('descripcion', 'textarea',array('attr'=>array('placeholder'=>'Ingrese una descripción...'),'required'=>false, 'label'=>'Descripción'))
            ->add('precioCosto', 'money', array( 'attr'=>array('placeholder'=>'##.##' , 'onkeyup'=>'aumentarMargen(0)'), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false, 'label'=>'Precio de costo'
                ))
            ->add('margen', 'number',  array( 'attr'=>array('placeholder'=>'##.##' , 'onkeyup'=>'aumentarMargen(1)'), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false, 'label'=>'Margen'
                ))
              ->add('margenMin', 'number',  array( 'attr'=>array('placeholder'=>'##.##' ,'tipo'=>'' ,'onkeyup'=>'margenMax(this);'), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false, 'label'=>'Margen mínimo'
                ))
            ->add('precioVenta',  'money', array( 'attr'=>array('placeholder'=>'##.##' , 'onkeyup'=>'aumentarMargen(2)'), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false, 'label'=>'Precio de venta'
                ))
             ->add('iva', 'entity', array('class' => "SisGG\FinalBundle\Entity\IVA", 'empty_value' => 'Seleccione una tasa de IVA.', 'label'=>'IVA', 'required'=>true,'query_builder' => function ( $repository) {
                            return $repository->createQueryBuilder('i')->where("i.activo=1");
                        }))
            ->add('cantidad', 'number', array( 'attr'=>array('placeholder'=>'##.##'), 
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false
                ))

            ->add('cantidadMinima', 'number', array( 'attr'=>array('placeholder'=>'##.##'),  
                'invalid_message' => 'Debe ingresar datos numéricos.','required'=>false,'label'=>'Cantidad mínima'
                ))
            ->add('proveedor',null, array('attr'=>array('onchange'=>'prov=this.options[this.selectedIndex].value')))
            ->add('tasa', 'entity', array('class' => "SisGG\FinalBundle\Entity\Tasa", 'empty_value' => false, 'required'=>true, 'query_builder' => function ($repository) {
                            return $repository->createQueryBuilder('p');
                        })) 
            
             ->add('categoria', 'entity', array('class' => "SisGG\FinalBundle\Entity\CategoriaProductoVenta", 'empty_value' => 'Seleccione una categoria', 'required'=>true, 'query_builder' => function ($repository) {
                            return $repository->createQueryBuilder('p')->where("p.activo=1");
                        }))
            ->add('foto',new ImageType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Mercaderia'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_mercaderiatype';
    }
}
