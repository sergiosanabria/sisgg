<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PedidoDeliveryType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('itemPedido', 'collection', array('required' => true, 'type' => new ItemPedidoType(), 'allow_add' => true, 'by_reference' => false, 'attr' => array('añadir' => false, 'forma' => 'simple')))
                ->add('cliente', null, array('required' => false, 'attr' => array('onchange' => 'cambioCliente(this);', 'style' => 'display:none;'), 'label_attr' => array('style' => 'display:none;')))
                ->add('solicitante', null, array('required' => true, 'label' => 'Apellido y Nombre'))
                ->add('direccion', new DireccionType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Pedido'
        ));
    }

    public function getName() {
        return 'pedidodeliverytype';
    }

}
