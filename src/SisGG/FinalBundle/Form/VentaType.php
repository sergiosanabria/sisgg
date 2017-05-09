<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class VentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha',null,array('widget'=>"single_text",'format' => 'dd/MM/yyyy'))
            ->add('nombre',null,array('label'=>'Razon Social'))
            ->add('cuit',null,array('label'=>'C.U.I.T.'))
            ->add('tipoFactura',null,array('label'=>'Tipo de Factura'))
            ->add('serie',null,array('label'=>'Punto de Venta'))
            ->add('numero')
            ->add('total')    
            ->add('condicionIva', 'entity', array(
                    'label' => 'Condición',
                    'required' => true,
                    'empty_value' => 'Seleccione una Condicion',
                    'class' => 'SisGGFinalBundle:CondicionIva',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->where("c.estado LIKE 'activo'");
                    },
                ))
            ->add('cliente')
            ->add('lineasVenta','collection',array('required'=>true,'type'=>new LineaVentaType(),'allow_add'=>true,'by_reference'=>false,'attr'=>array('añadir'=>false,'forma'=>'simple')))
            ->add('itemsRecargoVenta','collection',array('required'=>false,'type'=>new ItemRecargoVentaType(),'allow_add'=>true,'by_reference'=>false,'attr'=>array('añadir'=>false,'forma'=>'simple')))
            ->add('itemsDescuentoVenta','collection',array('required'=>false,'type'=>new ItemDescuentoVentaType(),'allow_add'=>true,'by_reference'=>false,'attr'=>array('añadir'=>false,'forma'=>'simple')))
            ->add('itemsIvaVenta','collection',array('required'=>false,'type'=>new ItemIvaVentaType(),'allow_add'=>true,'by_reference'=>false,'attr'=>array('añadir'=>false,'forma'=>'simple')))
            ->add('total')
            ->add('cobros','collection',array('required'=>true,'type'=>new CobroType(),'allow_add'=>true,'prototype_name'=>'__cobros__'))
            //->add('cobros','collection',array('required'=>true,'type'=>new CobroType(),'allow_add'=>true,'by_reference'=>false,'attr'=>array('forma'=>'simple','class'=>'cobro'),'prototype_name'=>'__cobros__'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Venta'
        ));
    }

    public function getName()
    {
        return 'ventatype';
    }
}
