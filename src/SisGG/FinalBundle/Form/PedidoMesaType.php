<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PedidoMesaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('itemPedido','collection',array('required'=>true,'type'=>new ItemPedidoType(),'allow_add'=>true,'by_reference'=>false,'attr'=>array('añadir'=>false,'forma'=>'simple')))
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Pedido'
        ));
    }

    public function getName()
    {
        return 'pedidomesatype';
    }
}
