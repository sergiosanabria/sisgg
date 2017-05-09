<?php
namespace SisGG\FinalBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\Telefono;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 */
class Backup {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
    * @Assert\NotBlank(message="Ingrese un directorio válido.") 
     * @ORM\Column(type="string")
     */
    private $carpeta;
    /**
     * @ORM\Column(type="string")
     */
    private $archivo;
      /**
     * @ORM\ManyToOne(targetEntity="Usuario",inversedBy="backups")
     */
    protected $usuario;
    
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha;
    
    
    public function getArchivo() {
        return $this->archivo;
    }

    public function setArchivo($archivo) {
        $this->archivo = $archivo;
    }

    public function getCarpeta() {
        return $this->carpeta;
    }

    public function setCarpeta($carpeta) {
        $this->carpeta = $carpeta;
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
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Backup
     */
    public function setUsuario(\SisGG\FinalBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \SisGG\FinalBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Backup
     */
    public function setFecha($fecha)
    {
        $this->fecha = new \DateTime('now') ;
    
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
}