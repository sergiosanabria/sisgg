<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CargoEmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, array('label'=>'Nombre del cargo', 'required'=>true, 'attr'=>array('tipo'=>'direccion','placeholder'=>'Ingrese el nombre')))
            ->add('tipo','choice', array('attr'=>array('onchange'=>"cambioFormaPago(this)"),'empty_value'=>'Seleccione una forma de pago','label'=>'Forma de pago','choices' => array( 0 => 'Por día de cada mes', 2=>'Por día de la semana', 1=>'Por días')))        
                ->add('descripcion', 'textarea',array('attr'=>array('placeholder'=>'Ingrese una descripción...'),'required'=>false, 'label'=>'Descripción'))
            ->add('monto', 'money', array('attr' => array('ayuda'=>'Monto que se le abonara al empleado peródicamente según la forma de pago.','onkeyup' => '','onblur' => 'lostFoc(this)')))
            ->add('negativo', 'money', array('attr' => array('ayuda'=>'Monto límite que se le podrá abonar por anticipo al empleado cuando no tenga haber.','onkeyup' => '','onblur' => 'lostFoc(this)')))
            ->add('descuento',null,array('required' => true,'attr' => array('tipo'=>'spinner','max'=>100,'min'=>0, 'ayuda' => 'Porcentaje de descuento por pago en especies.')))
            ->add('porDia', 'integer', array('attr' => array('ayuda'=>'','tipo'=>'spinner', 'min'=>1, 'max'=>31,'onblur' => 'lostFoc(this)'), 'label'=>'Cantidad de días'))
            ->add('porFecha', 'integer', array('attr' => array('tipo'=>'spinner', 'min'=>1, 'max'=>28,'onblur' => 'lostFoc(this)'), 'label'=>'Día del mes'))
            ->add('porDiaSemana','choice', array('label'=>'Día de la semana','choices' => array( 0 => 'Domingo', 1=>'Lunes', 2=>'Martes', 3=>'Miércoles', 4=>'Jueves', 5=>'Viernes', 6=>'Sábado')))
            ->add('cargoSistema', 'entity', array( 'attr'=>array('onchange'=>'obtenerCargo(this)'), 'class' => "SisGG\FinalBundle\Entity\CargoSistema",'empty_value'=>'Seleccione un cargo','required'=>'required' ,'query_builder' => function ($repository) {
                                return $repository->createQueryBuilder('p');
                            }))
            //->add('empleado')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\CargoEmpleado'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_cargoempleadotype';
    }
}
