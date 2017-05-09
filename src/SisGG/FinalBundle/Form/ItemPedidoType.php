<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemPedidoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('precioUnitario','number',array('label_attr'=>array('style'=>'display:none;'),'attr'=>array('style'=>'display:none;')))
            ->add('descuento','number',array('label_attr'=>array('style'=>'display:none;'),'attr'=>array('style'=>'display:none;')))
            ->add('productoVenta',null,array('required'=>true,'empty_value'=>'Seleccione un Producto','label_attr'=>array("style"=>'display:none;'),'attr'=>array("class"=>"productoVenta","style"=>'display:none;','onchange'=>'cargarProductoVenta(this)')))
            ->add('ingredientes',null,array('label'=>'Especificación de ingredientes','property'=>'exclusion','attr'=>array("class"=>"",'onchange'=>'hayCambio();'),'required' => false))
            ->add('tasa',null,array('required'=>true,'empty_value'=>'Seleccione una Tasa','label_attr'=>array("style"=>'display:none;'),'attr'=>array("class"=>"","style"=>'display:none;','onchange'=>'hayCambio();')))    
            ->add('cantidad',null,array('required'=>true,'empty_data'=>1,'attr'=>array('class'=>'','onchange'=>'hayCambio();')))     
            ->add('consideraciones','textarea',array('attr'=>array('class'=>'','placeholder'=>'Consideracion...','onchange'=>'hayCambio();'),'required' => false))
            ->add('estado','choice',array('required'=>true,'label_attr'=>array('style'=>'display:none;'),'attr'=>array('style'=>'display:none;'),'choices'=>array('nuevo'=>'Nuevo','actualizado'=>'Actualizado','enCocina'=>'En Cocina','listo'=>'Listo','entregado'=>'Entregado','cancelado'=>'Cancelado')))
            ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\ItemPedido'
        ));
    }

    public function getName()
    {
        return 'itempedido';
    }
}
