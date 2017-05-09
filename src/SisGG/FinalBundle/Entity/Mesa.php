<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Mesa implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\OneToOne(targetEntity="Pedido", mappedBy="mesa")
     * @Gedmo\Versioned
     */
    protected $pedidoActivo;
    /**
     * @ORM\ManyToOne(targetEntity="Sector",inversedBy="mesas")
     * @Gedmo\Versioned
     */
    protected $sector;
    /**
     * @ORM\Column(type="integer")
     * @Gedmo\Versioned
     */
    protected $numero;
    /**
     * @return string
     */
    public function serialize()
    {
      return serialize($this->id);
    }
    
    /**
     * @return string
     */
    public function __toString() {
        return "".$this->numero;
    }
    
    /**
     * @return string
     */
    public function getNombreCompleto() {
        return $this->sector->getNombre()." ".$this->numero;
    }
    
    /**
     * @return string
     */
    public function getEstado(){
        if ($this->getPedidoActivo() === null){
            $estado = "Libre";
        }else{
            $estado = "Ocupado";
        }
        return $estado;
    }
    
    
    
    /**
     * 
     * @param strine $estado
     */
    function setEstado($estado){
        
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
     * Constructor
     */
    public function __construct()
    {
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Mesa
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
     * Set pedidoActivo
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $pedidoActivo
     * @return Mesa
     */
    public function setPedidoActivo(\SisGG\FinalBundle\Entity\Pedido $pedidoActivo = null)
    {
        $this->pedidoActivo = $pedidoActivo;
    
        return $this;
    }

    /**
     * Get pedidoActivo
     *
     * @return \SisGG\FinalBundle\Entity\Pedido 
     */
    public function getPedidoActivo()
    {
        return $this->pedidoActivo;
    }

    /**
     * Set sector
     *
     * @param \SisGG\FinalBundle\Entity\Sector $sector
     * @return Mesa
     */
    public function setSector(\SisGG\FinalBundle\Entity\Sector $sector = null)
    {
        $this->sector = $sector;
    
        return $this;
    }

    /**
     * Get sector
     *
     * @return \SisGG\FinalBundle\Entity\Sector 
     */
    public function getSector()
    {
        return $this->sector;
    }
}