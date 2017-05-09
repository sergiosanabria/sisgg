<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SisGG\FinalBundle\Form\ImageType;
use Doctrine\ORM\EntityRepository;
class EmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, array('label'=>'Nombre', 'required'=>true))
            ->add('slogan',null, array('label'=>'Slogan', 'required'=>true))
            //->add('responsable','choice', array('label'=>'Condición de IVA','choices' => array( false => 'Monotributista', true=>'Responsable Inscripto')))        
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
            ->add('descripcion')
            ->add('cuit',null,array('attr'=>array('ayuda'=>'Formato: ##-########-#'),'required'=>false, 'label'=>'C.U.I.T.','pattern'=>"[0-9]{2}[-][0-9]{8}[-][0-9]{1}"))
            ->add('direccion',new DireccionType())
            ->add('edad', 'integer', array('attr'=>array('tipo'=>'spinner', 'max'=>80, 'min'=>10, 'ayuda'=>'Edad en años mínima con la cual puede trabajar en la empresa.')))
            ->add('email', 'email', array( 'required'=>true))
            ->add('contrasenia', null, array( 'required'=>true, 'label'=>'Contraseña'))
            ->add('inicioAct', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Inicio de actividades', 'widget' => 'single_text'))
            ->add('foto',new ImageType())   
            ->add('telefonos','collection',array('type'=>new TelefonoType(),'allow_add'=>true,'allow_delete'=>true,'by_reference'=>true))
            ->add('ip', null, array('attr'=>array('ayuda'=>'Dirección IP de la máquina servidora.'),'label'=>'Dirección IP' ,'pattern'=>'((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$'))                
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Empresa'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_empresatype';
    }
}
