<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Validator\Constraints as MyAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author martin
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class TipoOperacion implements \Serializable{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $nombre;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $descripcion;
    /**
     * @ORM\OneToMany(targetEntity="Venta",mappedBy="tipoOperacion")
     */
    private $ventas;
    /**
     * @ORM\OneToMany(targetEntity="Operacion",mappedBy="tipoOperacion")
     */
    private $operaciones;
    
    public function __toString() {
        return $this->getNombre();
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
        $this->ventas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TipoOperacion
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return TipoOperacion
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
     * Add ventas
     *
     * @param \SisGG\FinalBundle\Entity\Venta $ventas
     * @return TipoOperacion
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
     * Add operaciones
     *
     * @param \SisGG\FinalBundle\Entity\Operacion $operaciones
     * @return TipoOperacion
     */
    public function addOperacione(\SisGG\FinalBundle\Entity\Operacion $operaciones)
    {
        $this->operaciones[] = $operaciones;
    
        return $this;
    }

    /**
     * Remove operaciones
     *
     * @param \SisGG\FinalBundle\Entity\Operacion $operaciones
     */
    public function removeOperacione(\SisGG\FinalBundle\Entity\Operacion $operaciones)
    {
        $this->operaciones->removeElement($operaciones);
    }

    /**
     * Get operaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperaciones()
    {
        return $this->operaciones;
    }
}