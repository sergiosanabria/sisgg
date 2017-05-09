<?php

namespace SisGG\FinalBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\MensajeRepository")
 * @ORM\Table(name="mensaje")
 * @Gedmo\Loggable
 */

class Mensaje {
   /**
    * @var string $id
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mensaje;
    /**
     * @ORM\Column(type="boolean")
     */
    private $leido;
    /**
     * @ORM\Column(type="boolean")
     */
    private $nuevo;
    /**
     * @ORM\Column(type="integer")
     */
    private $tipo;
    /**
     * @ORM\ManyToOne(targetEntity="Usuario",inversedBy="mensajes")
     */
    protected $usuario;
    

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mensaje
     *
     * @param string $mensaje
     * @return Mensaje
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    
        return $this;
    }

    /**
     * Get mensaje
     *
     * @return string 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set leido
     *
     * @param boolean $leido
     * @return Mensaje
     */
    public function setLeido($leido)
    {
        $this->leido = $leido;
    
        return $this;
    }

    /**
     * Get leido
     *
     * @return boolean 
     */
    public function getLeido()
    {
        return $this->leido;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     * @return Mensaje
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set nuevo
     *
     * @param boolean $nuevo
     * @return Mensaje
     */
    public function setNuevo($nuevo)
    {
        $this->nuevo = $nuevo;
    
        return $this;
    }

    /**
     * Get nuevo
     *
     * @return boolean 
     */
    public function getNuevo()
    {
        return $this->nuevo;
    }

    

    /**
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Mensaje
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
}