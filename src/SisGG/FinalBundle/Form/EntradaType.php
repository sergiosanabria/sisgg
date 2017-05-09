<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
class EntradaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('t', 'choice', array('empty_value' => false, 'label' => 'Tipo de Movimiento', 'multiple' => false, 'expanded' => true, 'required' => true, 'choices' => array('Entrada' => 'Entrada', 'Salida' => 'Salida')))
                ->add('tipo', 'entity', array(
                    'required' => true,
                    'empty_value' => 'Seleccione una opción',
                    'class' => 'SisGGFinalBundle:TipoCobro',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                                ->where('t.liquido = true');
                    }
                ))
                ->add('concepto', null, array('required' => true, 'empty_data' => 'Seleccione una opción'))
                ->add('importe', null, array('required' => true, 'label' => 'Importe'))
                ->add('aclaracion', 'textarea', array('label' => 'Aclaración de Movimiento', 'required' => true))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Entrada'
        ));
    }

    public function getName() {
        return 'movimientotype';
    }

}
