<?php

namespace SisGG\FinalBundle\Form;

use SisGG\FinalBundle\Form\ItemEspeciesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \DateTime;

class EspeciesEmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $hoy = new DateTime('now');
        $hoy = date_format($hoy, 'd/m/Y');
        $builder
            //->add('monto','number', array('attr'=>array('tipo'=>'spinner', 'min'=>0, 'onkeyup'=>'cambiarMontoCanc(this)','onblur'=>'lostFoc(this)')))
            ->add('fecha', 'date',array('format' => 'dd/MM/yyyy','widget'=>'single_text', 'attr'=>array('value'=>$hoy)))
            ->add('descripcion', 'textarea',array('required'=>false, 'label'=>'Descripción'))
            ->add('monto', 'money', array('read_only' => true,'attr' => array('class'=>'input-medium','dir'=>'rtl')))
                ->add('item', 'collection', array('type' => new ItemEspeciesType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\EspeciesEmpleado'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_especiesempleadotype';
    }
}
