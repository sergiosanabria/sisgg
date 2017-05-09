<?php
namespace SisGG\FinalBundle\Entity;

use SisGG\FinalBundle\Entity\Pedido;
use SisGG\FinalBundle\Entity\PersonaEmpleado;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\ItemPedido;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\RegistroEnvioRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class RegistroEnvio implements \Serializable{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="PersonaEmpleado",inversedBy="envios")
     * @Gedmo\Versioned
     */
    private $empleado;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $estado;
    
    /**
      * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    private $totalPedidos;
    
    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    private $totalRendido;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Gedmo\Versioned
     */
    private $numero;
    
    /**
     * @ORM\OneToMany(targetEntity="Pedido",mappedBy="registroEnvio")
     */
    private $pedido;
    
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $fecha;
    
    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Gedmo\Versioned
     */
    private $fechaRendicion;
    
    /**
     * @return stringpedido
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
        $this->fecha = new \DateTime("now");
        $this->estado = 'activo';
        foreach ($this->pedido as $pedido){
            $this->totalPedidos = $this->totalPedidos + $pedido->getPrecio();
        }
    }
    
    public function __toString() {
        return "Registro de Envio ".$this->getNumeroRegistro();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedido = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set estado
     *
     * @param string $estado
     * @return RegistroEnvio
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
     * Set numero
     *
     * @param integer $numero
     * @return RegistroEnvio
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @return string
     */
    public function getNumeroRegistro() {
        return str_pad($this->getId(), 8, 0, STR_PAD_LEFT);
    }
    /**
     * Set empleado
     *
     * @param \SisGG\FinalBundle\Entity\PersonaEmpleado $empleado
     * @return RegistroEnvio
     */
    public function setEmpleado(\SisGG\FinalBundle\Entity\PersonaEmpleado $empleado = null)
    {
        $this->empleado = $empleado;
    
        return $this;
    }

    /**
     * Get empleado
     *
     * @return \SisGG\FinalBundle\Entity\PersonaEmpleado 
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Add pedido
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $pedido
     * @return RegistroEnvio
     */
    public function addPedido(\SisGG\FinalBundle\Entity\Pedido $pedido)
    {
        $this->pedido[] = $pedido;
    
        return $this;
    }

    /**
     * Remove pedido
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $pedido
     */
    public function removePedido(\SisGG\FinalBundle\Entity\Pedido $pedido)
    {
        $this->pedido->removeElement($pedido);
    }

    /**
     * Get pedido
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * Set totalPedidos
     *
     * @param string $totalPedidos
     * @return RegistroEnvio
     */
    public function setTotalPedidos($totalPedidos)
    {
        $this->totalPedidos = $totalPedidos;
    
        return $this;
    }

    /**
     * Get totalPedidos
     *
     * @return string 
     */
    public function getTotalPedidos()
    {
        return $this->totalPedidos;
    }

    /**
     * Set totalRendido
     *
     * @param string $totalRendido
     * @return RegistroEnvio
     */
    public function setTotalRendido($totalRendido)
    {
        $this->totalRendido = $totalRendido;
    
        return $this;
    }

    /**
     * Get totalRendido
     *
     * @return string 
     */
    public function getTotalRendido()
    {
        return $this->totalRendido;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return RegistroEnvio
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set fechaRendicion
     *
     * @param \DateTime $fechaRendicion
     * @return RegistroEnvio
     */
    public function setFechaRendicion($fechaRendicion)
    {
        $this->fechaRendicion = $fechaRendicion;
    
        return $this;
    }

    /**
     * Get fechaRendicion
     *
     * @return \DateTime 
     */
    public function getFechaRendicion()
    {
        return $this->fechaRendicion;
    }
}