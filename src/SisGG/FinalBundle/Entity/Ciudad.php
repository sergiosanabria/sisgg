<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(fields={"nombre", "provincia"}, message="Ya existe una ciudad en esta provincia con este nombre.")
 * @ORM\Entity
 */
class Ciudad implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Provincia",inversedBy="ciudad")
     */
    protected $provincia;
    /**
     * @ORM\Column(type="string",length=60)
     */
    private $nombre;
    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $codigoPostal;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    protected $porDefecto;
    
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

    public function __toString() {
        return $this->nombre.' ('.$this->codigoPostal.')';
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
     * @return Ciudad
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
     * Set codigoPostal
     *
     * @param integer $codigoPostal
     * @return Ciudad
     */
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigoPostal = $codigoPostal;
    
        return $this;
    }

    /**
     * Get codigoPostal
     *
     * @return integer 
     */
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
     * Set clientes
     *
     * @param SisGG\FinalBundle\Entity\Cliente $clientes
     * @return Ciudad
     */
    public function setClientes(\SisGG\FinalBundle\Entity\Cliente $clientes = null)
    {
        $this->clientes = $clientes;
    
        return $this;
    }

    /**
     * Get clientes
     *
     * @return SisGG\FinalBundle\Entity\Cliente 
     */
    public function getClientes()
    {
        return $this->clientes;
    }

    /**
     * Set provincia
     *
     * @param SisGG\FinalBundle\Entity\Provincia $provincia
     * @return Ciudad
     */
    public function setProvincia(\SisGG\FinalBundle\Entity\Provincia $provincia = null)
    {
        $this->provincia = $provincia;
    
        return $this;
    }

    /**
     * Get provincia
     *
     * @return SisGG\FinalBundle\Entity\Provincia 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set porDefecto
     *
     * @param boolean $porDefecto
     * @return Ciudad
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
}