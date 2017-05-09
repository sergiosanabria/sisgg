<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',null, array( 'attr'=>array('placeholder'=>'Ingrese el nombre'))) 
            ->add('nombreEmpresa', null, array('label'=>'Nombre de empresa', 'required'=>true, 'attr'=>array('placeholder'=>'Ingrese el nombre')))
            ->add('cuit',null,array('attr'=>array('ayuda'=>'Formato: ##-########-#'),'required'=>false, 'label'=>'C.U.I.T.','pattern'=>"[0-9]{2}[-][0-9]{8}[-][0-9]{1}"))
            ->add('descripcion', 'textarea',array('attr'=>array('placeholder'=>'Ingrese una descripción...'),'required'=>false, 'label'=>'Descripción'))
            ->add('codigo','text', array( 'required'=>false,'attr'=>array('placeholder'=>'Ingrese codigo') ,'label' =>'Código de cliente'))
        ;
        }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Servicio'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_serviciotype';
    }
}
