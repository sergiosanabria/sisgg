<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use SisGG\FinalBundle\Entity\ItemIvaVenta;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\VentaRepository")
 * @UniqueEntity({"serie","numero","tipoFactura"})
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class Venta implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario",inversedBy="ventas")
     * @Gedmo\Versioned
     */
    protected $usuario;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $fecha;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="LineaVenta",mappedBy="venta",cascade="persist")
     */
    protected $lineasVenta;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente",inversedBy="ventas")
     * @Gedmo\Versioned
     */
    protected $cliente;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="CondicionIva")
     * @Gedmo\Versioned
     */
    protected $condicionIva;

    /**
     * @ORM\ManyToOne(targetEntity="CondicionIva")
     * @Gedmo\Versioned
     */
    protected $condicionIvaEmpresa;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $cuit;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $estado;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $total;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $cambio;

    /**
     * @ORM\OneToOne(targetEntity="Pedido")
     * @Gedmo\Versioned
     */
    protected $pedido;

    /**
     * @Assert\Range(
     *      min = "1",
     *      max= "9998",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Assert\NotBlank(message="Ingrese el número de serie.")
     * @ORM\Column(type="string", length=4, nullable=false)
     * @Gedmo\Versioned
     */
    private $serie;

    /**
     * @Assert\Range(
     *      min = "1",
     *      max = "99999999",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Assert\NotBlank(message="Ingrese el número.")
     * @ORM\Column(type="string", length=8, nullable=false)
     * @Gedmo\Versioned
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity="Cobro",mappedBy="venta",cascade={"persist"})
     */
    protected $cobros;

    /**
     * @ORM\ManyToOne(targetEntity="TipoOperacion",inversedBy="ventas")
     * @Gedmo\Versioned
     */
    private $tipoOperacion;

    /**
     * @ORM\ManyToOne(targetEntity="Operacion",inversedBy="ventas")
     * @Gedmo\Versioned
     */
    private $operacion;

    /**
     * @ORM\ManyToOne(targetEntity="TipoFactura",inversedBy="ventas")
     * @Gedmo\Versioned
     */
    private $tipoFactura;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="ItemRecargoVenta",mappedBy="venta",cascade={"persist"})
     */
    public $itemsRecargoVenta;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="ItemDescuentoVenta",mappedBy="venta",cascade={"persist"})
     */
    public $itemsDescuentoVenta;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="ItemIvaVenta",mappedBy="venta",cascade={"persist"})
     */
    public $itemsIvaVenta;
    
    /**
     * @Assert\Valid
     * @ORM\OneToOne(targetEntity="MovimientoCuentaCorrienteDebe", mappedBy="unaVenta")
     */
    protected $movimientoCuentaCorriente;

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
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumeroFactura() {
        return str_pad($this->getSerie(), 4, 0, STR_PAD_LEFT) . '-' . str_pad($this->getNumero(), 8, 0, STR_PAD_LEFT);
    }

    public function __toString() {
        return $this->getTipoFactura() . " " . $this->getNumeroFactura();
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Venta
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * precio = Precio unitario neto (con bonificacion calculada) al que solo se calcula el monto de iva
     * @param float $precio
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     */
    public function sumarIva($precio, IVA $iva) {
        $itemIvaVenta = null;
        /* @var $item ItemIvaVenta */
        foreach ($this->getItemsIvaVenta() as $item) {
            if ($item->getIva() == $iva) {
                $itemIvaVenta = $item;
                $itemIvaVenta->setTotal($itemIvaVenta->getTotal() + ($precio - ($precio / ($iva->getTasa() / 100 + 1))));
            }
        }
        if ($itemIvaVenta == null) {
            $itemIvaVenta = new ItemIvaVenta();
            $itemIvaVenta->setTotal(($precio - ($precio / ($iva->getTasa() / 100 + 1))));
            $itemIvaVenta->setVenta($this);
            $itemIvaVenta->setGravado($iva->getGravado());
            $itemIvaVenta->setIva($iva);
            $itemIvaVenta->setTasa($iva->getTasa());
            $this->addItemsIvaVenta($itemIvaVenta);
        }
        return $itemIvaVenta;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Venta
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set condicionIva
     *
     * @param string $condicionIva
     * @return Venta
     */
    public function setCondicionIva($condicionIva) {
        $this->condicionIva = $condicionIva;

        return $this;
    }

    /**
     * Get condicionIva
     *
     * @return string 
     */
    public function getCondicionIva() {
        return $this->condicionIva;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     * @return Venta
     */
    public function setCuit($cuit) {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return string 
     */
    public function getCuit() {
        return $this->cuit;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Venta
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
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Venta
     */
    public function setUsuario(\SisGG\FinalBundle\Entity\Usuario $usuario = null) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \SisGG\FinalBundle\Entity\Usuario 
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Add lineasVenta
     *
     * @param \SisGG\FinalBundle\Entity\LineaVenta $lineasVenta
     * @return Venta
     */
    public function addLineasVenta(\SisGG\FinalBundle\Entity\LineaVenta $lineasVenta) {
        $this->lineasVenta[] = $lineasVenta;

        return $this;
    }

    /**
     * Remove lineasVenta
     *
     * @param \SisGG\FinalBundle\Entity\LineaVenta $lineasVenta
     */
    public function removeLineasVenta(\SisGG\FinalBundle\Entity\LineaVenta $lineasVenta) {
        $this->lineasVenta->removeElement($lineasVenta);
    }

    /**
     * Get lineasVenta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLineasVenta() {
        return $this->lineasVenta;
    }

    public function setLineasVenta(\Doctrine\Common\Collections\Collection $lineasVenta) {
        $this->lineasVenta = $lineasVenta;
    }

    /**
     * Set cliente
     *
     * @param \SisGG\FinalBundle\Entity\Cliente $cliente
     * @return Venta
     */
    public function setCliente(\SisGG\FinalBundle\Entity\Cliente $cliente = null) {
        $this->cliente = $cliente;
        return $this;
    }

    /**
     * Get cliente
     *
     * @return \SisGG\FinalBundle\Entity\Cliente 
     */
    public function getCliente() {
        return $this->cliente;
    }

    /**
     * Add cobros
     *
     * @param \SisGG\FinalBundle\Entity\Cobro $cobros
     * @return Venta
     */
    public function addCobro(\SisGG\FinalBundle\Entity\Cobro $cobros) {
        $this->cobros[] = $cobros;

        return $this;
    }

    /**
     * Remove cobros
     *
     * @param \SisGG\FinalBundle\Entity\Cobro $cobros
     */
    public function removeCobro(\SisGG\FinalBundle\Entity\Cobro $cobros) {
        $this->cobros->removeElement($cobros);
    }

    /**
     * Get cobros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCobros() {
        return $this->cobros;
    }

    /**
     * Set pedido
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $unPedido
     * @return Venta
     */
    public function setPedido(\SisGG\FinalBundle\Entity\Pedido $unPedido = null) {
        $this->pedido = $unPedido;

        if ($this->pedido != null) {
            foreach ($this->getLineasVenta() as $value) {
                $this->getLineasVenta()->removeElement($value);
            }
            /* @var $item \SisGG\FinalBundle\Entity\ItemPedido */
            foreach ($unPedido->getItemPedido() as $item) {
                if ($item->getEstado() != 'cancelado' && $item->getEstado() != 'Cancelado') {
                    $lineaP = $this->getLineaProductoVenta($item->getProductoVenta());
                    if ($lineaP != null) {
                        $lineaP->setCantidad($item->getCantidad() + $lineaP->getCantidad());
                    } else {
                        $linea = new LineaVenta();
                        $linea->setProductoVenta($item->getProductoVenta());
                        $linea->setCantidad($item->getCantidad());
                        $linea->setVenta($this);
                        $this->addLineasVenta($linea);
                    }
                }
            }
            foreach ($this->getItemsRecargoVenta() as $value) {
                $this->getItemsRecargoVenta()->removeElement($value);
            }
            /* @var $itemRecargo ItemRecargoPedido */
            foreach ($unPedido->getTipoPedido()->getRecargosActivos() as $recargo) {
                $itemRecargoVenta = new ItemRecargoVenta();
                $itemRecargoVenta->setVenta($this);
                $this->getItemsRecargoVenta()->add($itemRecargoVenta);
                $itemRecargoVenta->setRecargo($recargo);
            }
            if ($unPedido->getCliente() != null) {
                /* @var $itemDescuento ItemDescuentoPedido */
                foreach ($unPedido->getCliente()->getDescuentosActivos() as $descuento) {
                    $itemDescuentoVenta = new ItemDescuentoVenta();
                    $itemDescuentoVenta->setDescuento($descuento);
                    $itemDescuentoVenta->setVenta($this);
                    $this->getItemsDescuentoVenta()->add($itemDescuentoVenta);
                }
            }
        }
        return $this;
    }

    public function getSubtotal() {
        $retorno = 0.00;
        /* @var $linea LineaVenta */
        foreach ($this->getLineasVenta() as $linea) {
            $retorno = $retorno + $linea->getPrecioNeto();
        }
        /* @var $item ItemRecargoVenta */
        foreach ($this->getItemsRecargoVenta() as $item) {
            $retorno = $retorno + $item->getTotalRecargoConIva();
        }
        return $retorno;
    }

    public function getSubtotalSinIva() {
        $retorno = 0.00;
        /* @var $linea LineaVenta */
        foreach ($this->getLineasVenta() as $linea) {
            $retorno = $retorno + $linea->getPrecioNetoSinIva();
        }
        /* @var $item ItemRecargoVenta */
        foreach ($this->getItemsRecargoVenta() as $item) {
            $retorno = $retorno + $item->getTotalRecargoSinIva();
        }
        return $retorno;
    }

    public function getLineaProductoVenta(ProductoVenta $productoVenta) {
        $retorno = null;
        foreach ($this->getLineasVenta() as $linea) {
            if ($linea->getProductoVenta() == $productoVenta)
                $retorno = $linea;
        }
        return $retorno;
    }

    /**
     * Get pedido
     *
     * @return \SisGG\FinalBundle\Entity\Pedido 
     */
    public function getPedido() {
        return $this->pedido;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return Venta
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Set serie
     *
     * @param string $serie
     * @return Venta
     */
    public function setSerie($serie) {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return string 
     */
    public function getSerie() {
        return $this->serie;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Venta
     */
    public function setNumero($numero) {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * Set tipoOperacion
     *
     * @param \SisGG\FinalBundle\Entity\TipoOperacion $tipoOperacion
     * @return Venta
     */
    public function setTipoOperacion(\SisGG\FinalBundle\Entity\TipoOperacion $tipoOperacion = null) {
        $this->tipoOperacion = $tipoOperacion;

        return $this;
    }

    /**
     * Get tipoOperacion
     *
     * @return \SisGG\FinalBundle\Entity\TipoOperacion 
     */
    public function getTipoOperacion() {
        return $this->tipoOperacion;
    }

    /**
     * Set tipoFactura
     *
     * @param \SisGG\FinalBundle\Entity\TipoFactura $tipoFactura
     * @return Venta
     */
    public function setTipoFactura(\SisGG\FinalBundle\Entity\TipoFactura $tipoFactura = null) {
        $this->tipoFactura = $tipoFactura;

        return $this;
    }

    /**
     * Get tipoFactura
     *
     * @return \SisGG\FinalBundle\Entity\TipoFactura 
     */
    public function getTipoFactura() {
        return $this->tipoFactura;
    }

    /**
     * Add itemsDescuentoVenta
     *
     * @param \SisGG\FinalBundle\Entity\ItemDescuentoVenta $itemsDescuentoVenta
     * @return Venta
     */
    public function addItemsDescuentoVenta(\SisGG\FinalBundle\Entity\ItemDescuentoVenta $itemsDescuentoVenta) {
        $this->itemsDescuentoVenta[] = $itemsDescuentoVenta;

        return $this;
    }

    /**
     * Remove itemsDescuentoVenta
     *
     * @param \SisGG\FinalBundle\Entity\ItemDescuentoVenta $itemsDescuentoVenta
     */
    public function removeItemsDescuentoVenta(\SisGG\FinalBundle\Entity\ItemDescuentoVenta $itemsDescuentoVenta) {
        $this->itemsDescuentoVenta->removeElement($itemsDescuentoVenta);
    }

    /**
     * Get itemsDescuentoVenta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemsDescuentoVenta() {
        return $this->itemsDescuentoVenta;
    }

    /**
     * Add itemsRecargoVenta
     *
     * @param \SisGG\FinalBundle\Entity\ItemRecargoVenta $itemsRecargoVenta
     * @return Venta
     */
    public function addItemsRecargoVenta(\SisGG\FinalBundle\Entity\ItemRecargoVenta $itemsRecargoVenta) {
        $this->itemsRecargoVenta[] = $itemsRecargoVenta;

        return $this;
    }

    /**
     * Remove itemsRecargoVenta
     *
     * @param \SisGG\FinalBundle\Entity\ItemRecargoVenta $itemsRecargoVenta
     */
    public function removeItemsRecargoVenta(\SisGG\FinalBundle\Entity\ItemRecargoVenta $itemsRecargoVenta) {
        $this->itemsRecargoVenta->removeElement($itemsRecargoVenta);
    }

    /**
     * Get itemsRecargoVenta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemsRecargoVenta() {
        return $this->itemsRecargoVenta;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->lineasVenta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cobros = new \Doctrine\Common\Collections\ArrayCollection();
        $this->itemsRecargoVenta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->itemsDescuentoVenta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->itemsIvaVenta = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set cambio
     *
     * @param string $cambio
     * @return Venta
     */
    public function setCambio($cambio) {
        $this->cambio = $cambio;

        return $this;
    }

    /**
     * Get cambio
     *
     * @return string 
     */
    public function getCambio() {
        return $this->cambio;
    }

    /**
     * Set condicionIvaEmpresa
     *
     * @param \SisGG\FinalBundle\Entity\CondicionIva $condicionIvaEmpresa
     * @return Venta
     */
    public function setCondicionIvaEmpresa(\SisGG\FinalBundle\Entity\CondicionIva $condicionIvaEmpresa = null) {
        $this->condicionIvaEmpresa = $condicionIvaEmpresa;

        return $this;
    }

    /**
     * Get condicionIvaEmpresa
     *
     * @return \SisGG\FinalBundle\Entity\CondicionIva 
     */
    public function getCondicionIvaEmpresa() {
        return $this->condicionIvaEmpresa;
    }

    /**
     * Set operacion
     *
     * @param \SisGG\FinalBundle\Entity\Operacion $operacion
     * @return Venta
     */
    public function setOperacion(\SisGG\FinalBundle\Entity\Operacion $operacion = null) {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return \SisGG\FinalBundle\Entity\Operacion 
     */
    public function getOperacion() {
        return $this->operacion;
    }

    /**
     * Add itemsIvaVenta
     *
     * @param \SisGG\FinalBundle\Entity\ItemIvaVenta $itemsIvaVenta
     * @return Venta
     */
    public function addItemsIvaVenta(\SisGG\FinalBundle\Entity\ItemIvaVenta $itemsIvaVenta) {
        $this->itemsIvaVenta[] = $itemsIvaVenta;

        return $this;
    }

    /**
     * Remove itemsIvaVenta
     *
     * @param \SisGG\FinalBundle\Entity\ItemIvaVenta $itemsIvaVenta
     */
    public function removeItemsIvaVenta(\SisGG\FinalBundle\Entity\ItemIvaVenta $itemsIvaVenta) {
        $this->itemsIvaVenta->removeElement($itemsIvaVenta);
    }

    /**
     * Get itemsIvaVenta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemsIvaVenta() {
        return $this->itemsIvaVenta;
    }

    public function getTotalDescuento() {
        $descuento = 0.00;
        /* @var $item ItemDescuentoVenta */
        foreach ($this->getItemsDescuentoVenta() as $item) {
            $descuento = $item->getPorcentaje() + $descuento;
        }
        return $descuento;
    }

    public function calcularTotal() {
        foreach ($this->getItemsDescuentoVenta() as $item) {
            $item->calcularDescuentos($this->getSubtotal(), $this->getSubtotalSinIva());
        }
        /* @var $itemIva ItemIvaVenta */
        foreach ($this->getItemsIvaVenta() as $itemIva) {
            $itemIva->setTotal($itemIva->getTotal() - ($itemIva->getTotal() * $this->getTotalDescuento() / 100));
        }
        $this->setTotal($this->getSubtotal() - ($this->getSubtotal() * $this->getTotalDescuento() / 100));
    }


    /**
     * Set movimientoCuentaCorriente
     *
     * @param \SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteDebe $movimientoCuentaCorriente
     * @return Venta
     */
    public function setMovimientoCuentaCorriente(\SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteDebe $movimientoCuentaCorriente = null)
    {
        $this->movimientoCuentaCorriente = $movimientoCuentaCorriente;
    
        return $this;
    }

    /**
     * Get movimientoCuentaCorriente
     *
     * @return \SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteDebe 
     */
    public function getMovimientoCuentaCorriente()
    {
        return $this->movimientoCuentaCorriente;
    }
}