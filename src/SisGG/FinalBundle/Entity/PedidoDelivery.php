<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity
 */
class PedidoDelivery extends Pedido implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Cliente",inversedBy="pedidosDelivery")
     */
    private $cliente;
    /**
     * @ORM\OneToOne(targetEntity="Direccion")
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     */
    private $direccion;
    /**
     * @ORM\Column(type="date")
     */
    private $fecha;
    
    /**
     * @ORM\Column(type="time")
     */
    private $hora;
    
    /**
     * @return boolean
     */
    public function isDelivery(){
        return true;
    }
    /**
     * @return string
     */
    public function serialize()
    {
      return serialize($this->id);
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
     * Set cliente
     *
     * @param SisGG\FinalBundle\Entity\Cliente $cliente
     * @return PedidoDelivery
     */
    public function setCliente(\SisGG\FinalBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;
    
        return $this;
    }

    /**
     * Get cliente
     *
     * @return SisGG\FinalBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cliente = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add cliente
     *
     * @param SisGG\FinalBundle\Entity\Cliente $cliente
     * @return PedidoDelivery
     */
    public function addCliente(\SisGG\FinalBundle\Entity\Cliente $cliente)
    {
        $this->cliente[] = $cliente;
    
        return $this;
    }

    /**
     * Remove cliente
     *
     * @param SisGG\FinalBundle\Entity\Cliente $cliente
     */
    public function removeCliente(\SisGG\FinalBundle\Entity\Cliente $cliente)
    {
        $this->cliente->removeElement($cliente);
    }

    /**
     * Set direccion
     *
     * @param SisGG\FinalBundle\Entity\Direccion $direccion
     * @return PedidoDelivery
     */
    public function setDireccion(\SisGG\FinalBundle\Entity\Direccion $direccion = null)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return SisGG\FinalBundle\Entity\Direccion 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

   

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return PedidoDelivery
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
     * Set hora
     *
     * @param \DateTime $hora
     * @return PedidoDelivery
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    
        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime 
     */
    public function getHora()
    {
        return $this->hora;
    }
}