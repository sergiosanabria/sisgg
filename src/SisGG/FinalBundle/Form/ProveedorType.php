<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SisGG\FinalBundle\Form\DireccionType;
use SisGG\FinalBundle\Form\TelefonoType;
use Doctrine\ORM\EntityRepository;
class ProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('razonSocial', null, array('label'=>'Razón social', 'required'=>true))
            ->add('email', 'email', array( 'required'=>false))
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
            ->add('direccion',new DireccionType())
            ->add('cuit',null,array('attr'=>array('ayuda'=>'Formato: ##-########-#'),'required'=>false, 'label'=>'C.U.I.T.','pattern'=>"[0-9]{2}[-][0-9]{8}[-][0-9]{1}"))
            ->add('telefonos','collection',array('type'=>new TelefonoType(),'allow_add'=>true,'allow_delete'=>true,'by_reference'=>true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Proveedor'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_proveedortype';
    }
}
