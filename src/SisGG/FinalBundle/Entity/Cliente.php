<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Validator\Constraints as MyAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\ClienteRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class Cliente implements \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\Valid
     * @ORM\OneToOne(targetEntity="Telefono",cascade="persist")
     * @Gedmo\Versioned 
     */
    private $telefono;

    /**
     * @ORM\OneToOne(targetEntity="Direccion",cascade={"persist"})
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\Email(message="Ingrese un email valido")
     * @Gedmo\Versioned  
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Pedido",mappedBy="cliente")
     */
    private $pedidos;

    /**
     * @ORM\OneToMany(targetEntity="Venta",mappedBy="cliente")
     */
    private  $ventas;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned 
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="TipoDocumento")
     * @Gedmo\Versioned 
     */
    private $tipoDocumento;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\Regex(pattern="/\d/",
     *     match=true,
     *     message="El dni solo puede contener números")  
     * @Gedmo\Versioned 
     */
    private $documento;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Ingrese un apellido")
     * @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="El apellido no puede contener números") 
     * @Gedmo\Versioned 
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Ingrese un nombre")
     *  @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="El nombre no puede contener números") 
     * @Gedmo\Versioned
     */
    private $nombre;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $cuit;
          
    /**
     * @ORM\ManyToMany(targetEntity="DescuentoCliente",inversedBy="cliente")
     */
    private $descuentos;
    
    /**
     * @ORM\ManyToMany(targetEntity="GrupoCliente",mappedBy="clientes")
     */
    private $gruposCliente;
    
    /**
     * @ORM\ManyToOne(targetEntity="CondicionIva",inversedBy="clientes")
     * @Gedmo\Versioned
     */
    private $condicionIva;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $porDefecto;
    
    /**
     * @ORM\OneToOne(targetEntity="CuentaCorriente",mappedBy="cliente")
     * @Gedmo\Versioned
     */
    private $cuenta;
    
    /**
     * @return string
     */
    public function identificacion() {
        return "ninguna";
    }

    public function toArray() {
        $retorno = array('nombre'=>  "".$this,'cuit'=>  $this->getCuit(),'condicionIva'=> $this->getCondicionIva()->getId());
        return $retorno;
    }

    public function __toString() {
        return $this->apellido.', '.$this->nombre;
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
 
    
    public function getDescuentosActivos(){
        $retorno = array();
        foreach ($this->getDescuentos() as $descuento) {
            if ($descuento->getEstado() == 'activo')
                $retorno[ ] = $descuento;
        }
        foreach ($this->getGruposActivos() as $grupos) {
            foreach ($grupos->getDescuentosActivos() as $descuento) {
                if (!in_array($descuento, $retorno))
                    $retorno[ ] = $descuento;
            }
        }
        return $retorno;
    }
    
    public function getGruposActivos(){
        $retorno = array();
        foreach ($this->getGruposCliente() as $grupoCliente) {
            if ($grupoCliente->getEstado() == 'activo')
                $retorno[ ] = $grupoCliente;
        }
        return $retorno;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ventas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->descuentos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gruposCliente = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set email
     *
     * @param string $email
     * @return Cliente
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Cliente
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
     * Set documento
     *
     * @param string $documento
     * @return Cliente
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
    
        return $this;
    }

    /**
     * Get documento
     *
     * @return string 
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return Cliente
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    
        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Cliente
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
     * Set cuit
     *
     * @param string $cuit
     * @return Cliente
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;
    
        return $this;
    }

    /**
     * Get cuit
     *
     * @return string 
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set telefono
     *
     * @param \SisGG\FinalBundle\Entity\Telefono $telefono
     * @return Cliente
     */
    public function setTelefono(\SisGG\FinalBundle\Entity\Telefono $telefono = null)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return \SisGG\FinalBundle\Entity\Telefono 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set direccion
     *
     * @param \SisGG\FinalBundle\Entity\Direccion $direccion
     * @return Cliente
     */
    public function setDireccion(\SisGG\FinalBundle\Entity\Direccion $direccion = null)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return \SisGG\FinalBundle\Entity\Direccion 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Add pedidos
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $pedidos
     * @return Cliente
     */
    public function addPedido(\SisGG\FinalBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos[] = $pedidos;
    
        return $this;
    }

    /**
     * Remove pedidos
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $pedidos
     */
    public function removePedido(\SisGG\FinalBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos->removeElement($pedidos);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }

    /**
     * Add ventas
     *
     * @param \SisGG\FinalBundle\Entity\Venta $ventas
     * @return Cliente
     */
    public function addVenta(\SisGG\FinalBundle\Entity\Venta $ventas)
    {
        $this->ventas[] = $ventas;
    
        return $this;
    }

    /**
     * Remove ventas
     *
     * @param \SisGG\FinalBundle\Entity\Venta $ventas
     */
    public function removeVenta(\SisGG\FinalBundle\Entity\Venta $ventas)
    {
        $this->ventas->removeElement($ventas);
    }

    /**
     * Get ventas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVentas()
    {
        return $this->ventas;
    }

    /**
     * Set tipoDocumento
     *
     * @param \SisGG\FinalBundle\Entity\TipoDocumento $tipoDocumento
     * @return Cliente
     */
    public function setTipoDocumento(\SisGG\FinalBundle\Entity\TipoDocumento $tipoDocumento = null)
    {
        $this->tipoDocumento = $tipoDocumento;
    
        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return \SisGG\FinalBundle\Entity\TipoDocumento 
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Add descuentos
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoCliente $descuentos
     * @return Cliente
     */
    public function addDescuento(\SisGG\FinalBundle\Entity\DescuentoCliente $descuentos)
    {
        $this->descuentos[] = $descuentos;
    
        return $this;
    }

    /**
     * Remove descuentos
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoCliente $descuentos
     */
    public function removeDescuento(\SisGG\FinalBundle\Entity\DescuentoCliente $descuentos)
    {
        $this->descuentos->removeElement($descuentos);
    }

    /**
     * Get descuentos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescuentos()
    {
        return $this->descuentos;
    }

    /**
     * Add gruposCliente
     *
     * @param \SisGG\FinalBundle\Entity\GrupoCliente $gruposCliente
     * @return Cliente
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
     * Set condicionIva
     *
     * @param \SisGG\FinalBundle\Entity\CondicionIva $condicionIva
     * @return Cliente
     */
    public function setCondicionIva(\SisGG\FinalBundle\Entity\CondicionIva $condicionIva = null)
    {
        $this->condicionIva = $condicionIva;
    
        return $this;
    }

    /**
     * Get condicionIva
     *
     * @return \SisGG\FinalBundle\Entity\CondicionIva 
     */
    public function getCondicionIva()
    {
        return $this->condicionIva;
    }

    /**
     * Set porDefecto
     *
     * @param boolean $porDefecto
     * @return Cliente
     */
    public function setPorDefecto($porDefecto)
    {
        $this->porDefecto = $porDefecto;
    
        return $this;
    }

    /**
     * Get porDefecto
     *
     * @return boolean 
     */
    public function getPorDefecto()
    {
        return $this->porDefecto;
    }

    /**
     * Set cuenta
     *
     * @param \SisGG\FinalBundle\Entity\CuentaCorriente $cuenta
     * @return Cliente
     */
    public function setCuenta(\SisGG\FinalBundle\Entity\CuentaCorriente $cuenta = null)
    {
        $this->cuenta = $cuenta;
    
        return $this;
    }

    /**
     * Get cuenta
     *
     * @return \SisGG\FinalBundle\Entity\CuentaCorriente 
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }
}