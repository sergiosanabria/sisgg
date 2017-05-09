<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Validator\Constraints as MyAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 * @UniqueEntity(fields="nombre", message="Ya existe un descuento con este nombre. Ingrese otro.")
 */
class DescuentoCliente extends Descuento implements \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Cliente",mappedBy="descuentos")
     */
    protected $cliente;

    /**
     * @ORM\ManyToMany(targetEntity="GrupoCliente",mappedBy="descuentos")
     */
    protected $gruposCliente;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $importe;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $tipoPorcentaje;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $tipoImporte;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $minimo;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $maximo;
    
    /**
     * @ORM\ManyToOne(targetEntity="Venta",inversedBy="itemsDescuentoVenta")
     * @Gedmo\Versioned 
     */
    private $venta;

    public function __toString() {
        return $this->nombre;
    }

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
        $this->setEstado("activo");
    }

    

    /**
     * @var string
     */
    protected $nombre;

    /**
     * @var string
     */
    protected $porcentaje;

    /**
     * @var string
     */
    protected $descripcion;

    /**
     * @var string
     */
    protected $estado;

    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set importe
     *
     * @param string $importe
     * @return DescuentoCliente
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;
    
        return $this;
    }

    /**
     * Get importe
     *
     * @return string 
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set tipoPorcentaje
     *
     * @param boolean $tipoPorcentaje
     * @return DescuentoCliente
     */
    public function setTipoPorcentaje($tipoPorcentaje)
    {
        $this->tipoPorcentaje = $tipoPorcentaje;
    
        return $this;
    }

    /**
     * Get tipoPorcentaje
     *
     * @return boolean 
     */
    public function getTipoPorcentaje()
    {
        return $this->tipoPorcentaje;
    }

    /**
     * Set tipoImporte
     *
     * @param boolean $tipoImporte
     * @return DescuentoCliente
     */
    public function setTipoImporte($tipoImporte)
    {
        $this->tipoImporte = $tipoImporte;
    
        return $this;
    }

    /**
     * Get tipoImporte
     *
     * @return boolean 
     */
    public function getTipoImporte()
    {
        return $this->tipoImporte;
    }

    /**
     * Set minimo
     *
     * @param string $minimo
     * @return DescuentoCliente
     */
    public function setMinimo($minimo)
    {
        $this->minimo = $minimo;
    
        return $this;
    }

    /**
     * Get minimo
     *
     * @return string 
     */
    public function getMinimo()
    {
        return $this->minimo;
    }

    /**
     * Set maximo
     *
     * @param string $maximo
     * @return DescuentoCliente
     */
    public function setMaximo($maximo)
    {
        $this->maximo = $maximo;
    
        return $this;
    }

    /**
     * Get maximo
     *
     * @return string 
     */
    public function getMaximo()
    {
        return $this->maximo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return DescuentoCliente
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set porcentaje
     *
     * @param string $porcentaje
     * @return DescuentoCliente
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;
    
        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return string 
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return DescuentoCliente
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return DescuentoCliente
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Add cliente
     *
     * @param \SisGG\FinalBundle\Entity\Cliente $cliente
     * @return DescuentoCliente
     */
    public function addCliente(\SisGG\FinalBundle\Entity\Cliente $cliente)
    {
        $this->cliente[] = $cliente;
    
        return $this;
    }

    /**
     * Remove cliente
     *
     * @param \SisGG\FinalBundle\Entity\Cliente $cliente
     */
    public function removeCliente(\SisGG\FinalBundle\Entity\Cliente $cliente)
    {
        $this->cliente->removeElement($cliente);
    }

    /**
     * Get cliente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Add gruposCliente
     *
     * @param \SisGG\FinalBundle\Entity\GrupoCliente $gruposCliente
     * @return DescuentoCliente
     */
    public function addGruposCliente(\SisGG\FinalBundle\Entity\GrupoCliente $gruposCliente)
    {
        $this->gruposCliente[] = $gruposCliente;
    
        return $this;
    }

    /**
     * Remove gruposCliente
     *
     * @param \SisGG\FinalBundle\Entity\GrupoCliente $gruposCliente
     */
    public function removeGruposCliente(\SisGG\FinalBundle\Entity\GrupoCliente $gruposCliente)
    {
        $this->gruposCliente->removeElement($gruposCliente);
    }

    /**
     * Get gruposCliente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGruposCliente()
    {
        return $this->gruposCliente;
    }

   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cliente = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gruposCliente = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set venta
     *
     * @param \SisGG\FinalBundle\Entity\Venta $venta
     * @return DescuentoCliente
     */
    public function setVenta(\SisGG\FinalBundle\Entity\Venta $venta = null)
    {
        $this->venta = $venta;
    
        return $this;
    }

    /**
     * Get venta
     *
     * @return \SisGG\FinalBundle\Entity\Venta 
     */
    public function getVenta()
    {
        return $this->venta;
    }
}