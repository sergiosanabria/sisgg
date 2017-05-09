<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @author martin
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\OperacionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"tipoOperacion", "de","a", "tipoFactura"},
 *     errorPath="tipoOperacion",
 *     message="La operacion de la condicion de iva a la condicionde iva especificada ya existe."
 * )
 * @Gedmo\Loggable
 */
class Operacion implements \Serializable{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="TipoOperacion",inversedBy="operaciones")
     */
    private $tipoOperacion;
    /**
     * @ORM\ManyToOne(targetEntity="TipoFactura",inversedBy="operaciones")
     */
    private $tipoFactura;
    /**
     * @ORM\ManyToOne(targetEntity="CondicionIva",inversedBy="operacionesDe")
     */
    private $de;
    /**
     * @ORM\ManyToOne(targetEntity="CondicionIva",inversedBy="operacionesA")
     */
    private $a;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $estado;
    
    public function __toString() {
        return $this->getTipoOperacion()." de ".$this->getDe()." a ".$this->getA()."(".$this->getTipoFactura().")";
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tipoOperacion
     *
     * @param \SisGG\FinalBundle\Entity\TipoOperacion $tipoOperacion
     * @return Operacion
     */
    public function setTipoOperacion(\SisGG\FinalBundle\Entity\TipoOperacion $tipoOperacion = null)
    {
        $this->tipoOperacion = $tipoOperacion;
    
        return $this;
    }

    /**
     * Get tipoOperacion
     *
     * @return \SisGG\FinalBundle\Entity\TipoOperacion 
     */
    public function getTipoOperacion()
    {
        return $this->tipoOperacion;
    }

    /**
     * Set tipoFactura
     *
     * @param \SisGG\FinalBundle\Entity\TipoFactura $tipoFactura
     * @return Operacion
     */
    public function setTipoFactura(\SisGG\FinalBundle\Entity\TipoFactura $tipoFactura = null)
    {
        $this->tipoFactura = $tipoFactura;
    
        return $this;
    }

    /**
     * Get tipoFactura
     *
     * @return \SisGG\FinalBundle\Entity\TipoFactura 
     */
    public function getTipoFactura()
    {
        return $this->tipoFactura;
    }

    /**
     * Set de
     *
     * @param \SisGG\FinalBundle\Entity\CondicionIva $de
     * @return Operacion
     */
    public function setDe(\SisGG\FinalBundle\Entity\CondicionIva $de = null)
    {
        $this->de = $de;
    
        return $this;
    }

    /**
     * Get de
     *
     * @return \SisGG\FinalBundle\Entity\CondicionIva 
     */
    public function getDe()
    {
        return $this->de;
    }

   

    /**
     * Set a
     *
     * @param \SisGG\FinalBundle\Entity\CondicionIva $a
     * @return Operacion
     */
    public function setA(\SisGG\FinalBundle\Entity\CondicionIva $a = null)
    {
        $this->a = $a;
    
        return $this;
    }

    /**
     * Get a
     *
     * @return \SisGG\FinalBundle\Entity\CondicionIva 
     */
    public function getA()
    {
        return $this->a;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Operacion
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
}