<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity
 */
class PedidoMesa extends Pedido implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Mesa", inversedBy="pedidos")
     */
    private $mesa;
    /**
     * @return boolean
     */
    public function isMesa(){
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
     * Set mesa
     *
     * @param SisGG\FinalBundle\Entity\Mesa $mesa
     * @return PedidoMesa
     */
    public function setMesa(\SisGG\FinalBundle\Entity\Mesa $mesa = null)
    {
        $this->mesa = $mesa;
    
        return $this;
    }

    /**
     * Get mesa
     *
     * @return SisGG\FinalBundle\Entity\Mesa 
     */
    public function getMesa()
    {
        return $this->mesa;
    }
}