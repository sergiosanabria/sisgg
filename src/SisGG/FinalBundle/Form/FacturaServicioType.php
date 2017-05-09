<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FacturaServicioType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($options['attr']['tipo']) {
            $builder
                    ->add('fechaFactura', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Fecha de factura', 'widget' => 'single_text'))
                    ->add('monto', 'number', array('attr' => array('placeholder' => '##.##'),
                        'invalid_message' => 'Debe ingresar datos numéricos.', 'required' => true
                    ))
                    ->add('otrosImp', 'number', array('attr' => array('placeholder' => '##.##'),
                        'invalid_message' => 'Debe ingresar datos numéricos.', 'required' => true
                    ))
                    ->add('periodo')
                    ->add('total', 'money', array('read_only' => true))
                    ->add('serie', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1)))
                    ->add('numero', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1)))
                    ->add('pagos', 'collection', array('type' => new PagoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                    //->add('fechaEmision')
                    ->add('servicio', 'entity', array('attr'=>array('class'=>'span4'),'class' => "SisGG\FinalBundle\Entity\Servicio", 'query_builder' => function ($repository) {
                            return $repository->createQueryBuilder('s')->where("s.activo=1")->orderBy('s.nombre', 'asc');
                        }))
            ;
        } else {
            $builder
                    ->add('fechaFactura', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Fecha de factura', 'widget' => 'single_text'))
                    ->add('total', 'money', array('attr' => array('placeholder' => '##.##'),
                        'invalid_message' => 'Debe ingresar datos numéricos.', 'required' => true
                    ))
                    ->add('serie', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1)))
                    ->add('periodo')
                    ->add('numero', 'integer', array('attr' => array('tipo' => 'spinner', 'min' => 1)))
                    ->add('pagos', 'collection', array('type' => new PagoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
            //->add('fechaEmision')
                    ->add('servicio', 'entity', array('attr'=>array('class'=>'span4'),'class' => "SisGG\FinalBundle\Entity\Servicio", 'query_builder' => function ($repository) {
                            return $repository->createQueryBuilder('s')->where("s.activo=1")->orderBy('s.nombre', 'asc');
                        }))
            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\FacturaServicio'
        ));
    }

    public function getName() {
        return 'sisgg_finalbundle_facturaserviciotype';
    }

}
