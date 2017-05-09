<?php

namespace SisGG\FinalBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use SisGG\FinalBundle\Form\DireccionType;
use SisGG\FinalBundle\Entity\Pedido;
use SisGG\FinalBundle\Entity\Direccion;
use Doctrine\ORM\EntityManager;
use SisGG\FinalBundle\Entity\TipoPedido;

/**
 * Description of AddCamposSubscriber
 *
 * @author martin
 */
class AddPedidoFieldsSubscriber implements EventSubscriberInterface
{

    private $factory;
    private $em;

    public function __construct(FormFactoryInterface $factory, EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_BIND => 'preBind'
        );
    }

    private function addFieldsForm($form, $tipoPedido, $cliente, $direccion, $mesa, $solicitante)
    {
        $hijos = $form->all();
        foreach ($hijos as $hijo) {
            $form->remove($hijo->getName());
        }
        $form->add($this->factory->createNamed('tipoPedido', 'entity', $tipoPedido, array(
            'class' => 'SisGGFinalBundle:tipoPedido',
            'label' => 'Tipo',
            'required' => true,
            'auto_initialize' => false
        )));
        if ($tipoPedido instanceof TipoPedido) {
            if ($tipoPedido->isTipo('mesa')) {
                $form->add($this->factory->createNamed('mesa', 'entity', $mesa, array(
                    'class' => 'SisGGFinalBundle:Mesa',
                    'empty_value' => 'Mesa',
                    'property' => 'nombreCompleto',
                    'required' => true,
                )));
            } else if ($tipoPedido->isTipo('mostrador')) {
                $form->add($this->factory->createNamed('cliente', 'entity', $cliente, array(
                    'class' => 'SisGGFinalBundle:Cliente',
                    'empty_value' => 'Cliente',
                    'required' => false,
                )));
                $form->add($this->factory->createNamed('solicitante', 'text', $solicitante, array(
                    'required' => true,
                    'label' => 'Apellido y Nombre',
                )));
            } else if ($tipoPedido->isTipo('delivery')) {
                $form->add($this->factory->createNamed('cliente', 'entity', $cliente, array(
                    'class' => 'SisGGFinalBundle:Cliente',
                    'empty_value' => 'Cliente',
                    'required' => false,
                )));
                $form->add($this->factory->createNamed('solicitante', 'text', $solicitante, array(
                    'required' => true,
                    'label' => 'Apellido y Nombre',
                )));
                $form->add($this->factory->createNamed('direccion', new DireccionType(), $direccion));
            }
        } elseif (is_numeric($tipoPedido)) {
            if ($tipoPedido == 1) {
                $form->add($this->factory->createNamed('mesa', 'entity', $mesa, array(
                    'class' => 'SisGGFinalBundle:Mesa',
                    'empty_value' => 'Mesa',
                    'property' => 'nombreCompleto',
                    'required' => true,
                )));
            } else if ($tipoPedido == 2) {
                $form->add($this->factory->createNamed('cliente', 'entity', $cliente, array(
                    'class' => 'SisGGFinalBundle:Cliente',
                    'empty_value' => 'Cliente',
                    'required' => false,
                )));
                $form->add($this->factory->createNamed('solicitante', 'text', $solicitante, array(
                    'required' => true,
                    'label' => 'Apellido y Nombre',
                )));
            } else if ($tipoPedido == 3) {
                $form->add($this->factory->createNamed('cliente', 'entity', $cliente, array(
                    'class' => 'SisGGFinalBundle:Cliente',
                    'empty_value' => 'Cliente',
                    'required' => false,
                )));
                $form->add($this->factory->createNamed('solicitante', 'text', $solicitante, array(
                    'required' => true,
                    'label' => 'Apellido y Nombre',
                )));
                $form->add($this->factory->createNamed('direccion', new DireccionType(), $direccion));
            }
        } else {
            if ($tipoPedido == 'mesa') {
                $form->add($this->factory->createNamed('mesa', 'entity', $mesa, array(
                    'class' => 'SisGGFinalBundle:Mesa',
                    'empty_value' => 'Mesa',
                    'property' => 'nombreCompleto',
                    'required' => true,
                )));
            } else if ($tipoPedido == 'mostrador') {
                $form->add($this->factory->createNamed('cliente', 'entity', $cliente, array(
                    'class' => 'SisGGFinalBundle:Cliente',
                    'empty_value' => 'Cliente',
                    'required' => false,
                )));
                $form->add($this->factory->createNamed('solicitante', 'text', $solicitante, array(
                    'required' => true,
                    'label' => 'Apellido y Nombre',
                )));
            } else if ($tipoPedido == 'delivery') {
                $form->add($this->factory->createNamed('cliente', 'entity', $cliente, array(
                    'class' => 'SisGGFinalBundle:Cliente',
                    'empty_value' => 'Cliente',
                    'required' => false,
                )));
                $form->add($this->factory->createNamed('solicitante', 'text', $solicitante, array(
                    'required' => true,
                    'label' => 'Apellido y Nombre',
                )));
                $form->add($this->factory->createNamed('direccion', new DireccionType(), $direccion));
            }
        }
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
        /* @var $data Pedido */
        $tipoPedido = $data->getTipoPedido();
        $cliente = $data->getCliente();
        $direccion = $data->getDireccion();
        $mesa = $data->getMesa();
        $solicitante = $data->getSolicitante();
        $this->addFieldsForm($form, $tipoPedido, $cliente, $direccion, $mesa, $solicitante);
    }

    public function preBind(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $tipoPedido = array_key_exists('tipoPedido', $data) ? $data['tipoPedido'] : null;
        $cliente = array_key_exists('cliente', $data) ? $data['cliente'] : null;
        $direccion = array_key_exists('direccion', $data) ? $data['direccion'] : null;
        $mesa = array_key_exists('mesa', $data) ? $data['mesa'] : null;
        $solicitante = array_key_exists('solicitante', $data) ? $data['solicitante'] : null;

        $d = null;
        if ($direccion != null) {
            $d = new Direccion();
            $d->setCalle(array_key_exists('calle', $direccion) ? $direccion['calle'] : null);
            $d->setNumero(array_key_exists('numero', $direccion) ? $direccion['numero'] : null);
            $d->setManzana(array_key_exists('manzana', $direccion) ? $direccion['manzana'] : null);
            $d->setEdificio(array_key_exists('edificio', $direccion) ? $direccion['edificio'] : null);
            $d->setEscalera(array_key_exists('escalera', $direccion) ? $direccion['escalera'] : null);
            $d->setPiso(array_key_exists('piso', $direccion) ? $direccion['piso'] : null);
            $d->setDepartamento(array_key_exists('departamento', $direccion) ? $direccion['departamento'] : null);
            $d->setCiudad(array_key_exists('ciudad', $direccion) ? $this->em->getRepository("SisGGFinalBundle:Ciudad")->find($direccion['ciudad']) : null);
        }

        $this->addFieldsForm($form, $tipoPedido, $cliente, $d, $mesa, $solicitante);
    }

}

?>
