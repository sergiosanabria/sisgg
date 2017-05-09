<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use SisGG\FinalBundle\Entity\Mercaderia;
use SisGG\FinalBundle\Entity\ItemPedido;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\TandaRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class Tanda implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Pedido",mappedBy="tanda")
     */
    protected $comandas;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cocina",inversedBy="tandas",cascade={"persist","remove"})
     * @Gedmo\Versioned 
     */
    private $cocina;

    /**
     * @ORM\OneToMany(targetEntity="LineaTanda",mappedBy="tanda",cascade={"persist","remove"})
     */
    protected $lineasTanda;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $estado;

    /**
     * @return string
     */
    public function serialize() {
        return serialize($this->id);
    }

    /**
     * @param string $data
     */
    public function unserialize($data) {
        $this->id = unserialize($data);
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->estado = "Vigente";
    }

    /**
     * @param \SisGG\FinalBundle\Entity\Pedido $comanda
     */
    public function vincularComanda(Pedido $comanda) {
        if (!$comanda->getTanda() == $this) {
            foreach ($comanda->getItemPedido() as $itemPedido) {
                if ($itemPedido->getEstado() != 'listo' && $itemPedido->getEstado() != 'cancelado' && $itemPedido->getEstado() != 'Cancelado' && $itemPedido->getEstado() != 'Listo' && !($itemPedido->getProductoVenta() instanceof Mercaderia))
                $this->vincularItem($itemPedido);
            }
            $this->addComanda($comanda);
            $comanda->setTanda($this);
        }
    }

    public function vincularItem(ItemPedido $itemPedido) {
        $linea = $this->existeLinea($itemPedido);
        if ($linea != null) {
            $linea->setCantidad($linea->getCantidad() + $itemPedido->getCantidad());
        } else {
            $linea = new LineaTanda();
            $linea->setCantidad($itemPedido->getCantidad());
            $linea->setProductoVenta($itemPedido->getProductoVenta());
            foreach ($itemPedido->getIngredientes() as $ingrediente) {
                $linea->addIngrediente($ingrediente);
            }
            $linea->setTanda($this);
            $this->addLineasTanda($linea);
        }
        $itemPedido->setEstado('En Cocina');
        $itemPedido->setVinculado(true);
    }

    /**
     * @param \SisGG\FinalBundle\Entity\Pedido $comanda
     */
    public function desvincularComanda(Pedido $comanda) {
        /*@var $itemPedido ItemPedido*/
        foreach ($comanda->getItemPedido() as $itemPedido) {
            if ($itemPedido->getEstado() != 'listo' && $itemPedido->getEstado() != 'cancelado' && $itemPedido->getEstado() != 'Cancelado' && $itemPedido->getEstado() != 'Listo' && ($itemPedido->getVinculado() != null && $itemPedido->getVinculado()!= false ) && !($itemPedido->getProductoVenta() instanceof Mercaderia))
                    $this->desvincularItem($itemPedido);
        }
        $this->removeComanda($comanda);
        $comanda->setTanda(null);
    }

    public function desvincularItem(ItemPedido $itemPedido) {
        $linea = $this->existeLinea($itemPedido);
        $linea->setCantidad($linea->getCantidad() - $itemPedido->getCantidad());
        if ($linea->getCantidad() == 0 && $linea->getCantidadElaborados() == 0) {
            $linea->setTanda(null);
            $this->removeLineasTanda($linea);
        }
        $itemPedido->setVinculado(false);
    }

    public function despacharPedido(Pedido $pedido) {
        $lineas = array();
        $retorno = true;
        foreach ($this->getLineasTanda() as $linea)
            $lineas [] = array('linea' => $linea, 'elaborados' => $linea->getCantidadElaborados());
        foreach ($pedido->getItemPedido() as $itemPedido) {
            foreach ($lineas as $linea) {
                if ($linea['linea']->esIgual($itemPedido)) {
                    if ($linea['elaborados'] - $itemPedido->getCantidad() >= 0) {
                        $linea['elaborados'] = $linea['elaborados'] - $itemPedido->getCantidad();
                    } else {
                        $retorno = false;
                    }
                }
            }
        }
        if ($retorno) {
            foreach ($pedido->getItemPedido() as $itemPedido) {
                if ($itemPedido->getEstado() != 'listo' && $itemPedido->getEstado() != 'cancelado' && $itemPedido->getEstado() != 'Cancelado' && $itemPedido->getEstado() != 'Listo' && !($itemPedido->getProductoVenta() instanceof Mercaderia)){
                 foreach ($this->getLineasTanda() as $linea) {
                    if ($linea->esIgual($itemPedido)) {
                        $linea->setCantidadElaborados($linea->getCantidadElaborados() - $itemPedido->getCantidad());
                        $linea->setCantidad($linea->getCantidad() - $itemPedido->getCantidad());
                        $itemPedido->setEstado("Listo");
                        if ($linea->getCantidad() == 0 && $linea->getCantidadElaborados() == 0) {
                            $linea->setTanda(null);
                            $this->removeLineasTanda($linea);
                        }
                    }
                    }
                }
            }
            $this->removeComanda($pedido);
        }
        return $retorno;
    }
    
    public function despacharItem(ItemPedido $itemPedido) {
        $lineas = array();
        $retorno = true;
        foreach ($this->getLineasTanda() as $linea)
            $lineas [] = array('linea' => $linea, 'elaborados' => $linea->getCantidadElaborados());
            foreach ($lineas as $linea) {
                if ($linea['linea']->esIgual($itemPedido)) {
                    if ($linea['elaborados'] - $itemPedido->getCantidad() >= 0) {
                        $linea['elaborados'] = $linea['elaborados'] - $itemPedido->getCantidad();
                    } else {
                        $retorno = false;
                    }
                }
            }
        if ($retorno) {
                foreach ($this->getLineasTanda() as $linea) {
                    if ($linea->esIgual($itemPedido)) {
                        $linea->setCantidadElaborados($linea->getCantidadElaborados() - $itemPedido->getCantidad());
                        $linea->setCantidad($linea->getCantidad() - $itemPedido->getCantidad());
                        $itemPedido->setEstado("Listo");
                        if ($linea->getCantidad() == 0 && $linea->getCantidadElaborados() == 0) {
                            $linea->setTanda(null);
                            $this->removeLineasTanda($linea);
                        }
                    }
                }
            }
        if ($itemPedido->getPedido()->isListo()){
            $this->removeComanda($itemPedido->getPedido());
        }    
        return $retorno;
    }

    /**
     * 
     * @param \SisGG\FinalBundle\Entity\ItemPedido $itemPedido
     * @return LineaTanda
     */
    public function existeLinea(\SisGG\FinalBundle\Entity\ItemPedido $itemPedido) {
        $retorno = null;
        foreach ($this->lineasTanda as $lineaTanda) {
            if ($lineaTanda->esIgual($itemPedido)) {
                $retorno = $lineaTanda;
            }
        }
        return $retorno;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->comandas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Tanda
     */
    public function setEstado($estado) {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * Add comandas
     *
     * @param SisGG\FinalBundle\Entity\Pedido $comandas
     * @return Tanda
     */
    public function addComanda(\SisGG\FinalBundle\Entity\Pedido $comandas) {
        $this->comandas[] = $comandas;

        return $this;
    }

    /**
     * Remove comandas
     *
     * @param SisGG\FinalBundle\Entity\Pedido $comandas
     */
    public function removeComanda(\SisGG\FinalBundle\Entity\Pedido $comandas) {
        $this->comandas->removeElement($comandas);
    }

    /**
     * Get comandas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComandas() {
        return $this->comandas;
    }

    /**
     * Add lineasTanda
     *
     * @param SisGG\FinalBundle\Entity\LineaTanda $lineasTanda
     * @return Tanda
     */
    public function addLineasTanda(\SisGG\FinalBundle\Entity\LineaTanda $lineasTanda) {
        $this->lineasTanda[] = $lineasTanda;

        return $this;
    }

    /**
     * Remove lineasTanda
     *
     * @param SisGG\FinalBundle\Entity\LineaTanda $lineasTanda
     */
    public function removeLineasTanda(\SisGG\FinalBundle\Entity\LineaTanda $lineasTanda) {
        $this->lineasTanda->removeElement($lineasTanda);
    }

    /**
     * Get lineasTanda
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLineasTanda() {
        return $this->lineasTanda;
    }


    /**
     * Set cocina
     *
     * @param \SisGG\FinalBundle\Entity\Cocina $cocina
     * @return Tanda
     */
    public function setCocina(\SisGG\FinalBundle\Entity\Cocina $cocina = null)
    {
        $this->cocina = $cocina;
    
        return $this;
    }

    /**
     * Get cocina
     *
     * @return \SisGG\FinalBundle\Entity\Cocina 
     */
    public function getCocina()
    {
        return $this->cocina;
    }
}