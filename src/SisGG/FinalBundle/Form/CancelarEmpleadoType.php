<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \DateTime;

class CancelarEmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hoy = new DateTime('now');
        $hoy = date_format($hoy, 'd/m/Y');
        $builder
            ->add('monto','number', array('attr'=>array('tipo'=>'', 'min'=>0, 'onkeyup'=>'cambiarMontoCanc(this)','onblur'=>'lostFoc(this)')))
            ->add('fecha', 'date',array('format' => 'dd/MM/yyyy','widget'=>'single_text', 'attr'=>array('value'=>$hoy)))
            ->add('descripcion', 'textarea',array('required'=>false, 'label'=>'Descripción'))
//          ->add('cuenta')
//            ->add('fechaEmision')
;
      
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\CancelarEmpleado'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_cancelarempleadotype';
    }
}
