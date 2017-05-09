<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SisGG\FinalBundle\Form\EventListener\AddPedidoFieldsSubscriber;
use Doctrine\ORM\EntityManager;
class PedidoType extends AbstractType
{
    /*@var $em EntityManager*/
    private $em;
    
    function __construct($em) {
        $this->em = $em;
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $factory = $builder->getFormFactory();
        $pedidoSubscriber = new AddPedidoFieldsSubscriber($factory,  $this->em);
        $builder->addEventSubscriber($pedidoSubscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Pedido'
        ));
    }

    public function getName()
    {
        return 'pedidotype';
    }
}
