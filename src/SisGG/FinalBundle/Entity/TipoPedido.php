<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\ItemPedido;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\PedidoRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class TipoPedido {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $nombre;
    
    /**
     * @ORM\ManyToMany(targetEntity="Recargo",mappedBy="tiposPedidos")
     */
    protected $recargos;
    
    /**
     * @ORM\OneToMany(targetEntity="Pedido",mappedBy="tipoPedido")
     */
    private $pedidos;
    
        
    public function __toString() {
        return ucfirst($this->nombre);
    }
    
    /**
     * @param string $nombre
     * @return boolean
     */
    public function isTipo($nombre){
        return $this->nombre==$nombre || $this->nombre==ucfirst($nombre); 
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
     * Constructor
     */
    public function __construct()
    {
        $this->recargos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return TipoPedido
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
     * Add recargos
     *
     * @param \SisGG\FinalBundle\Entity\Recargo $recargos
     * @return TipoPedido
     */
    public function addRecargo(\SisGG\FinalBundle\Entity\Recargo $recargos)
    {
        $this->recargos[] = $recargos;
    
        return $this;
    }

    /**
     * Remove recargos
     *
     * @param \SisGG\FinalBundle\Entity\Recargo $recargos
     */
    public function removeRecargo(\SisGG\FinalBundle\Entity\Recargo $recargos)
    {
        $this->recargos->removeElement($recargos);
    }

    /**
     * Get recargos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecargos()
    {
        return $this->recargos;
    }

    /**
     * Add pedidos
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $pedidos
     * @return TipoPedido
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
    
    public function getRecargosActivos(){
        $retorno = array();
        foreach ($this->getRecargos() as $recargo) {
            if ($recargo->isActivo()){
                $retorno[ ] = $recargo;
            }
        }
        return $retorno;
    }
}