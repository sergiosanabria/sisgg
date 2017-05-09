<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ChatMensaje {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="Ingrese el mensaje.")
     */
    private $mensaje;
   /**
     *@ORM\ManyToOne(targetEntity="Usuario")
     *@ORM\JoinColumn(name="destino", referencedColumnName="id", nullable=true)
     */
    private $destino;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $eliminar;
    //no leido 1
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $leido;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $envio;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaEnvio;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaRecibo;
    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     */
    
    private $usuario;

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
     * Set mensaje
     *
     * @param string $mensaje
     * @return ChatMensaje
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
     * @return ChatMensaje
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
     * Set envio
     *
     * @param boolean $envio
     * @return ChatMensaje
     */
    public function setEnvio($envio)
    {
        $this->envio = $envio;
    
        return $this;
    }

    /**
     * Get envio
     *
     * @return boolean 
     */
    public function getEnvio()
    {
        return $this->envio;
    }

    /**
     * Set fechaEnvio
     *
     * @param \DateTime $fechaEnvio
     * @return ChatMensaje
     */
    public function setFechaEnvio()
    {
        $this->fechaEnvio = new \DateTime("now");
        return $this;
    }

    /**
     * Get fechaEnvio
     *
     * @return \DateTime 
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }

    /**
     * Set fechaRecibo
     *
     * @param \DateTime $fechaRecibo
     * @return ChatMensaje
     */
    public function setFechaRecibo()
    {
        $this->fechaRecibo = new \DateTime("now");
        return $this;
    }

    /**
     * Get fechaRecibo
     *
     * @return \DateTime 
     */
    public function getFechaRecibo()
    {
        return $this->fechaRecibo;
    }

    /**
     * Set destino
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $destino
     * @return ChatMensaje
     */
    public function setDestino(\SisGG\FinalBundle\Entity\Usuario $destino = null)
    {
        $this->destino = $destino;
    
        return $this;
    }

    /**
     * Get destino
     *
     * @return \SisGG\FinalBundle\Entity\Usuario 
     */
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return ChatMensaje
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
     * Set eliminar
     *
     * @param integer $eliminar
     * @return ChatMensaje
     */
    public function setEliminar($eliminar)
    {
        $this->eliminar = $eliminar;
    
        return $this;
    }

    /**
     * Get eliminar
     *
     * @return integer 
     */
    public function getEliminar()
    {
        return $this->eliminar;
    }
}