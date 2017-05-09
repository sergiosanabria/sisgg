<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity
 */
class PedidoMostrador extends Pedido implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
   /**
     * @ORM\ManyToOne(targetEntity="Cliente",inversedBy="pedidosMostrador")
     */
    private $cliente;
    /**
     * @return boolean
     */
    public function isMostrador(){
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
     * @param string $data
     */
    public function unserialize($data)
    {
      $this->id = unserialize($data);
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
     * @return PedidoMostrador
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
     * @return PedidoMostrador
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
}