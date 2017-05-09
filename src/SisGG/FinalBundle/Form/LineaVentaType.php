<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class LineaVentaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('cantidad', null, array('required' => true,'empty_data'=>1, 'attr' => array('onchange'=>'totalLinea(this);','class' => 'span12')))
                ->add('productoVenta', null, array('required' => false,'empty_data'=>null, 'attr' => array('class' => 'span12 producto', 'onchange' => 'cargarProductoVenta(this);')))
                ->add('descripcion', null, array('required' => true,'empty_data'=>1, 'attr' => array('onchange'=>'totalLinea(this);','class' => 'span12')))
                ->add('tasa', null, array('required' => true, 'attr' => array('class' => 'span12')))
                ->add('precioUnitario', null, array('required' => true, 'attr' => array('class' => 'span12 precio','onchange'=>'totalLinea(this);')))
                ->add('precioUnitarioSinIva', null, array('required' => true, 'attr' => array('class' => 'span12 precioSinIva','onchange'=>'totalLinea(this);')))
                ->add('tasaIva', null, array('required' => true, 'attr' => array('class' => 'span12')))
                ->add('ivaProductoVenta', null, array('required' => true, 'attr' => array('class' => 'span12 iva')))
                ->add('bonificacion', null, array('required' => true, 'attr' => array('onchange'=>'totalLinea(this);','class' => 'span12')))            
                ->add('precioNeto', null, array('attr' => array('onchange'=>'total();','class' => 'span12')))
                ->add('precioNetoSinIva', null, array('attr' => array('onchange'=>'total();','class' => 'span12')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\LineaVenta'
        ));
    }

    public function getName() {
        return 'lineaventatype';
    }

}
