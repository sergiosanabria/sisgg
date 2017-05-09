<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotaPedidoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $items = new ItemNotaPedidoType( );
        $items->setTipo($options['attr']['clase']);
        if ($options['attr']['clase'])
            $items->setProductos($options['attr']['productos']);
        $builder
                //->add('fecha')
                //->add('estado')
                ->add('total', 'money', array('read_only'=>true))
                ->add('proveedor', 'entity' , array('class'=>"SisGG\FinalBundle\Entity\Proveedor", 'query_builder' => function ($repository) { return $repository->createQueryBuilder('p')->where("p.activo=1")->orderBy('p.razonSocial', 'asc');} ))
                ->add('item', 'collection', array('type' => $items, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\NotaPedido'
        ));
    }

    public function getName() {
        return 'sisgg_finalbundle_notapedidotype';
    }

}
