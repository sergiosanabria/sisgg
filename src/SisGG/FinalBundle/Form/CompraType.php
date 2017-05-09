<?php

namespace SisGG\FinalBundle\Form;

use SisGG\FinalBundle\Form\ItemCompraType;
use SisGG\FinalBundle\Form\PagoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompraType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
      
        $hoy = new \DateTime('now');
        $hoy = date_format($hoy, 'd/m/Y');
        if ($options['attr']['clase'] == 1){
            $item = new ItemCompraType();
            $item->setTipo($options['attr']['tipo']);
            if ($options['attr']['tipo']=='A'){
                $builder
                        ->add('fechaFactura', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Fecha de factura', 'widget' => 'single_text'))
                        //->add('notaPedido')
                        ->add('tipo', 'text', array('read_only'=>true))
                       ->add('serie', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1,'max'=>9998),'label'=>'Punto de venta'))
                        ->add('numero', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1, 'max'=>99999999)))
                        ->add('total', 'money', array('read_only' => true,'attr' => array('class'=>'input-medium','dir'=>'rtl')))
                        ->add('proveedor', 'entity', array('class' => "SisGG\FinalBundle\Entity\Proveedor", 'query_builder' => function ($repository) {
                                return $repository->createQueryBuilder('p')->innerJoin('p.condicionIva', 'c')->innerJoin('c.operacionesA', 'o')->innerJoin('o.tipoFactura', 't')->where("p.activo=1 and t.nomenclatura='A'")->orderBy('p.razonSocial', 'asc');
                            }))
                        ->add('item', 'collection', array('type' => $item, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                        ->add('pagos', 'collection', array('type' => new PagoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                        ->add('tipos', 'choice', array(
                                'choices'   => array(
                                    '1'   => 'Modificar tasas',
                                    '2' => 'Modificar precios de costo (Si el IVA se descuenta el precio será el descontado.)',
                                    '3'   => 'Modificar IVA',
                                    '4'   => 'Modificar proveedor',
                                ),
                                'multiple'  => true,'expanded' => true,'label'=>'Opciones de modificación'
                            ))
                        ->add('descuento', 'money', array('attr' => array('class'=>'input-medium','dir'=>'rtl','onkeyup' => 'sumarIvas(this)', 'placeholder'=>'##.##', 'onblur'=>'lostFoc(this)')))
                        ->add('otrosImp', 'money', array('label'=>'Otros impuestos','attr' => array('class'=>'input-medium','dir'=>'rtl','onkeyup' => 'sumarIvas(this)', 'placeholder'=>'##.##', 'onblur'=>'lostFoc(this)')))    
                 // ->add('fechaEmision')
                // ->add('estado')
                //->add('notaPedido')
                ;}
            elseif ($options['attr']['tipo']=='B'){
                $builder
                        ->add('fechaFactura', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Fecha de factura', 'widget' => 'single_text'))
                        //->add('notaPedido')
                        ->add('tipo', 'text', array('read_only'=>true))
                        ->add('serie', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1,'max'=>9998),'label'=>'Punto de venta'))
                        ->add('numero', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1, 'max'=>99999999)))
                        ->add('total', 'money', array('read_only' => true,'attr'=>array('dir'=>'rtl',)))
                        ->add('proveedor', 'entity', array('class' => "SisGG\FinalBundle\Entity\Proveedor", 'query_builder' => function ($repository) {
                                return $repository->createQueryBuilder('p')->innerJoin('p.condicionIva', 'c')->innerJoin('c.operacionesA', 'o')->innerJoin('o.tipoFactura', 't')->where("p.activo=1 and t.nomenclatura='B'")->orderBy('p.razonSocial', 'asc');
                            }))
                        ->add('item', 'collection', array('type' => $item, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                        ->add('pagos', 'collection', array('type' => new PagoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                        ->add('tipos', 'choice', array(
                                'choices'   => array(
                                    '1'   => 'Modificar tasas',
                                    '2' => 'Modificar precios de costo',
                                    '4'   => 'Modificar proveedor',
                                ),
                                'multiple'  => true,'label'=>'Opciones de modificación',
                            'expanded' => true,
                            ))
                       ->add('descuento', 'money', array('attr' => array('onkeyup' => 'sumarTotal(this)','dir'=>'rtl', 'placeholder'=>'##.##', 'onblur'=>'lostFoc(this)')))
                       ->add('otrosImp', 'money', array('label'=>'Otros impuestos','attr' => array('class'=>'input-medium','dir'=>'rtl','onkeyup' => 'sumarTotal(this)', 'placeholder'=>'##.##', 'onblur'=>'lostFoc(this)')))    
                    // ->add('fechaEmision')
                // ->add('estado')
                //->add('notaPedido')
                ;
                            
            } elseif ($options['attr']['tipo']=='C'){
                     $builder
                        ->add('fechaFactura', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Fecha de factura', 'widget' => 'single_text'))
                        //->add('notaPedido')
                        ->add('tipo', 'text', array('read_only'=>true))
                        ->add('serie', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1,'max'=>9998),'label'=>'Punto de venta'))
                        ->add('numero', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1, 'max'=>99999999)))
                        ->add('total', 'money', array('read_only' => true,'attr'=>array('dir'=>'rtl',)))
                        ->add('proveedor', 'entity', array('class' => "SisGG\FinalBundle\Entity\Proveedor", 'query_builder' => function ($repository) {
                                return $repository->createQueryBuilder('p')->innerJoin('p.condicionIva', 'c')->innerJoin('c.operacionesA', 'o')->innerJoin('o.tipoFactura', 't')->where("p.activo=1 and t.nomenclatura='C'")->orderBy('p.razonSocial', 'asc');
                            }))
                        ->add('item', 'collection', array('type' => $item, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                        ->add('pagos', 'collection', array('type' => new PagoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                        ->add('tipos', 'choice', array(
                                'choices'   => array(
                                    '1'   => 'Modificar tasas',
                                    '2' => 'Modificar precios de costo',
                                    '4'   => 'Modificar proveedor',
                                ),
                                'multiple'  => true,'label'=>'Opciones de modificación',
                            'expanded' => true,
                            ))
                        ->add('otrosImp', 'money', array('label'=>'Otros impuestos','attr' => array('class'=>'input-medium','dir'=>'rtl','onkeyup' => 'sumarTotal(this)', 'placeholder'=>'##.##', 'onblur'=>'lostFoc(this)')))    
                       ->add('descuento', 'money', array('attr' => array('onkeyup' => 'sumarTotal(this)', 'dir'=>'rtl','placeholder'=>'##.##', 'onblur'=>'lostFoc(this)')))
                 // ->add('fechaEmision')
                // ->add('estado')
                //->add('notaPedido')
                ;
                } elseif ($options['attr']['tipo']=='X'){
                     $builder
                        ->add('fechaFactura', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Fecha de factura', 'widget' => 'single_text'))
                        //->add('notaPedido')
                        ->add('tipo', 'text', array('read_only'=>true))
                        ->add('serie', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1,'max'=>9998),'label'=>'Punto de venta'))
                        ->add('numero', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1, 'max'=>99999999)))
                        ->add('total', 'money', array('read_only' => true, 'attr'=>array('dir'=>'rtl',)))
                        ->add('proveedor', 'entity', array('class' => "SisGG\FinalBundle\Entity\Proveedor", 'query_builder' => function ($repository) {
                                return $repository->createQueryBuilder('p')->innerJoin('p.condicionIva', 'c')->innerJoin('c.operacionesA', 'o')->innerJoin('o.tipoFactura', 't')->where("p.activo=1 and t.nomenclatura='X'")->orderBy('p.razonSocial', 'asc');
                            }))
                        ->add('item', 'collection', array('type' => $item, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                        ->add('pagos', 'collection', array('type' => new PagoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                        ->add('tipos', 'choice', array(
                                'choices'   => array(
                                    '1'   => 'Modificar tasas',
                                    '2' => 'Modificar precios de costo',
                                    '4'   => 'Modificar proveedor',
                                ),
                                'multiple'  => true,'label'=>'Opciones de modificación',
                            'expanded' => true,
                            ))
                        ->add('otrosImp', 'money', array('label'=>'Otros impuestos','attr' => array('class'=>'input-medium','dir'=>'rtl','onkeyup' => 'sumarTotal(this)', 'placeholder'=>'##.##', 'onblur'=>'lostFoc(this)')))    
                       ->add('descuento', 'money', array('attr' => array('onkeyup' => 'sumarTotal(this)','dir'=>'rtl', 'placeholder'=>'##.##', 'onblur'=>'lostFoc(this)')))
                 // ->add('fechaEmision')
                // ->add('estado')
                //->add('notaPedido')
                ;
                }
        } 
        else {
            $builder->add('pagos', 'collection', array('type' => new PagoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Compra'
        ));
    }

    public function getName() {
        return 'sisgg_finalbundle_compratype';
    }

}
