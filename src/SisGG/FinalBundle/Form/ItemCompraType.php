<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemCompraType extends AbstractType {
    private $tipo;
    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($this->tipo=='A'){
            $builder
                    ->add('producto', 'entity', array( 'attr' => array( 'tipo' => 'buscar','buscarClick'=>'buscarEnCombo' ,'onchange' => 'cambioProducto(this)'), 'class' => "SisGG\FinalBundle\Entity\Producto", 'query_builder' => function (\SisGG\FinalBundle\Entity\ProductoRepository $repository) {
                            return $repository->createQueryBuilder('p')->where("p.activo=1");
                        }))
                    //->add('producto', 'choice',array( 'choices'=>  $this->pasarProducto() ,'attr'=>array('tipo'=>'buscar' ,'onchange'=>'cambioProducto(this)')))
                    ->add('cantidad', 'number', array('attr' => array('class'=>'input-mini','dir'=>'rtl','onkeyup' => 'cambiarCantidad (this)')))
                    ->add('tasa')//, 'choice', array('choices'=>  $this->pasarTasa(), 'preferred_choices'=>array($this->productos['productos'][0]->getTasa()->getId())))
                    ->add('precioCosto', 'number', array('attr' => array('class'=>'input-mini','dir'=>'rtl', 'onkeyup' => 'cambiarCosto(this)')))//, null, array('attr'=>array('value'=>$this->productos['tasas'])))            
                    ->add('iva', 'entity', array('class' => "SisGG\FinalBundle\Entity\IVA", 'empty_value' => 'Seleccione una tasa de IVA.', 'label'=>'IVA', 'required'=>true,'query_builder' => function ( $repository) {
                            return $repository->createQueryBuilder('i')->where("i.activo=1");
                        }))    
                    ->add('descuento', 'checkbox', array('required'  => false,'attr'=>array('title'=>'Sumar el porcentage de IVA al precio de costo.', 'onchange'=>'cambioCheck(this)')))
                     ->add('bonificacion', 'number', array('attr' => array('class'=>'input-mini','dir'=>'rtl','onkeyup' => 'cambiarDesc(this)', 'onblur'=>'lostFoc(this)')))
                    ->add('total', 'money', array('read_only' => true,'attr' => array('class'=>'input-medium','dir'=>'rtl'))) ;
        }else{
             $builder
                    ->add('producto', 'entity', array( 'attr' => array('tipo' => 'buscar','buscarClick'=>'buscarEnCombo' , 'onchange' => 'cambioProducto(this)'), 'class' => "SisGG\FinalBundle\Entity\Producto", 'query_builder' => function (\SisGG\FinalBundle\Entity\ProductoRepository $repository) {
                            return $repository->createQueryBuilder('p')->where("p.activo=1");
                        }))
                    //->add('producto', 'choice',array( 'choices'=>  $this->pasarProducto() ,'attr'=>array('tipo'=>'buscar' ,'onchange'=>'cambioProducto(this)')))
                    ->add('cantidad', 'number', array('attr' => array('class'=>'input-mini','dir'=>'rtl','onkeyup' => 'cambiarCantidad (this)')))
                    ->add('tasa')//, 'choice', array('choices'=>  $this->pasarTasa(), 'preferred_choices'=>array($this->productos['productos'][0]->getTasa()->getId())))
                    ->add('precioCosto', 'number', array('attr' => array('class'=>'input-mini','dir'=>'rtl','onkeyup' => 'cambiarCosto(this)')))//, null, array('attr'=>array('value'=>$this->productos['tasas'])))            
                     ->add('bonificacion', 'number', array('attr' => array('class'=>'input-mini','dir'=>'rtl','onkeyup' => 'cambiarDesc(this)', 'onblur'=>'lostFoc(this)')))
                    ->add('total', 'money', array('read_only' => true,'attr' => array('class'=>'input-medium','dir'=>'rtl'))) ;
        }

//                        $builder
//            ->add('cantidad')
//            ->add('precioCosto')
//            ->add('total')
//            ->add('producto')
//            ->add('tasa')
//            ->add('compra')
//        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\ItemCompra'
        ));
    }

    public function getName() {
        return 'sisgg_finalbundle_itemcompratype';
    }
   
    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
         $this->tipo=$tipo;
        return $this;
    }
    
    
    

}
