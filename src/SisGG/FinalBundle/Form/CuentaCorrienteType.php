<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CuentaCorrienteType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('numeroCuenta',null,array('label'=>'Número de Cuenta'))
                ->add('cliente', 'entity', array(
                    'class' => 'SisGGFinalBundle:Cliente',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->orderBy('c.apellido,c.nombre', 'ASC')
                                ->where("c.estado like 'activo'")
                                ;
                    },
                ))
                //->add('plazoGeneracionRecibos', null, array('required'=>true,'label' => "Plazo de vencimiento"))
                //->add('maximo', null, array('label' => "Máximo"))
                ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\CuentaCorriente'
        ));
    }

    public function getName() {
        return 'cuentacorrientetype';
    }

}
