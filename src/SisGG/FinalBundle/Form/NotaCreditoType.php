<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotaCreditoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha',null,array('widget'=>"single_text",'format' => 'dd/MM/yyyy'))
            ->add('nombre',null,array('label'=>'Razon Social'))
            ->add('cuit',null,array('label'=>'C.U.I.T.'))
            ->add('serie',null,array('label'=>'Serie'))
            ->add('numero')
            ->add('cliente')
            ->add('condicionIva')
            ->add('condicionIvaEmpresa')
            ->add('factura')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\NotaCredito'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_notacreditotype';
    }
}
