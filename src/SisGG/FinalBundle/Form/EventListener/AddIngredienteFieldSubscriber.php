<?php

namespace SisGG\FinalBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use SisGG\FinalBundle\Entity\ProductoVenta;

/**
 * Description of AddIngredienteFieldSubscriber
 *
 * @author martin
 */
class AddIngredienteFieldSubscriber implements EventSubscriberInterface {
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
    
    private function addIngredienteForm($form,$ingrediente,$productoVenta){
        $form->add($this->factory->createNamed('ingredientes','entity', $ingrediente, array(
            'class'         =>  'SisGGFinalBundle:Ingrediente',
            'label'         =>  'Especificación de ingredientes',
            'property'      =>  'exclusion',
            'attr'          =>  array("class"=>"ingrediente"),
            'required'      =>  false,
            'multiple'      =>  true,
            'query_builder' =>  function (EntityRepository $repository) use ($productoVenta) {
                $qb = $repository->createQueryBuilder('ingrediente')
                    ->innerJoin('ingrediente.plato', 'productoVenta');
                if($productoVenta instanceof ProductoVenta){
                    $qb->where('ingrediente.plato = :productoVenta and ingrediente.obligatorio = false')
                    ->setParameter('productoVenta', $productoVenta);
                }elseif(is_numeric($productoVenta)){
                    $qb->where('productoVenta.id = :productoVenta and ingrediente.obligatorio = false')
                    ->setParameter('productoVenta', $productoVenta);
                }else{
                    $qb->where('productoVenta.nombre = :productoVenta and ingrediente.obligatorio = false')
                    ->setParameter('productoVenta', null);
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
        $ingrediente = ($productoVenta) ? $productoVenta->getIngredientes() : null ;
        $this->addIngredienteForm($form, $productoVenta, $ingrediente);
    }
 
    public function preBind(FormEvent $event){
        $data = $event->getData();
        $form = $event->getForm();
 
        if (null === $data) {
            return;
        }
 
        $ingrediente = array_key_exists('ingredientes', $data) ? $data['ingredientes'] : null;
        $productoVenta = array_key_exists('productoVenta', $data) ? $data['productoVenta'] : null;
        $this->addIngredienteForm($form, $ingrediente, $productoVenta);
    }
}

?>
