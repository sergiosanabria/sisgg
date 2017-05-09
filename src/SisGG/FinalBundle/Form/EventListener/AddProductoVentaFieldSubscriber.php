<?php

namespace SisGG\FinalBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use SisGG\FinalBundle\Entity\ProductoVenta;

/**
 * Description of AddProductoVentaFieldSubscriber
 *
 * @author martin
 */
class AddProductoVentaFieldSubscriber implements EventSubscriberInterface {

    private $factory;

    public function __construct(FormFactoryInterface $factory) {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_BIND => 'preBind'
        );
    }

    private function addProductoVentaForm($form, $productoVenta) {
        $form->add($this->factory->createNamed('productoVenta', 'entity', $productoVenta, array(
                    'class' => 'SisGGFinalBundle:ProductoVenta',
                    'query_builder' => function(EntityRepository $repository){
                         $qb = $repository->createQueryBuilder('productoVenta')->where('productoVenta.activo = true');
 
                         return $qb;
                    },
                    'required'=>false,
                    'empty_value'=>'Seleccione un Producto',
                    'attr'=>array("class"=>"productoVenta",'onchange'=>'cambioProductoVenta();')        
        )));
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $productoVenta = $data->getProductoVenta();
        $this->addProductoVentaForm($form, $productoVenta);
    }

    public function preBind(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $productoVenta = array_key_exists('productoVenta', $data) ? $data['productoVenta'] : null;
        $this->addProductoVentaForm($form, $productoVenta);
    }

}

?>
