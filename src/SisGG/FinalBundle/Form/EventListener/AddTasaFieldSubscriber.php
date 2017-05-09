<?php

namespace SisGG\FinalBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use SisGG\FinalBundle\Entity\ProductoVenta;

/**
 * Description of AddTasaFieldSubscriber
 *
 * @author martin
 */
class AddTasaFieldSubscriber implements EventSubscriberInterface{
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
    
    private function addTasaForm($form,$tasa,$productoVenta){
        $form->add($this->factory->createNamed('tasa','entity', $tasa, array(
            'class'         =>  'SisGGFinalBundle:Tasa',
            'label'         =>  'Tasa',
            'attr'          =>  array("class"=>"tasa"),
            'required'      =>  false,
            'query_builder' =>  function (EntityRepository $repository) use ($productoVenta) {
                $qb = $repository->createQueryBuilder('tasa')
                    ->innerJoin('tasa.productosVenta', 'productoVenta');
                if($productoVenta instanceof ProductoVenta){
                    $qb->where('tasa.productosVenta = :productoVenta')
                    ->setParameter('productoVenta', $productoVenta);
                }elseif(is_numeric($productoVenta)){
                    $qb->where('productoVenta.id = :productoVenta')
                    ->setParameter('productoVenta', $productoVenta);
                }else{
                    $qb->where('productoVenta.tasa = :productoVenta')
                    ->setParameter('productoVenta', $productoVenta);
                }
 
                return $qb;
            }
        )));
    }
    
    public function preSetData(FormEvent $event){
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $productoVenta = $data->getProductoVenta() ;
        $tasa = ($productoVenta) ? $productoVenta->getTasa() : null ;
        $this->addTasaForm($form, $tasa, $productoVenta);
    }
 
    public function preBind(FormEvent $event){
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $tasa = array_key_exists('tasa', $data) ? $data['tasa'] : null;
        $productoVenta = array_key_exists('productoVenta', $data) ? $data['productoVenta'] : null;
        $this->addTasaForm($form, $tasa, $productoVenta);
    }
}

?>
