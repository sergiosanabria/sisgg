<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"inicioFec", "inicioHora", "finFec", "finHora", "asunto", "usuario"}, message="Ya existe un evento con esta fecha.")
 */
class Agenda {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ingrese el asunto.")
     */
    private $asunto;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lugar;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $todos;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=true)
     */
    private $inicioFec;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=true)
     */
    private $finFec;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="time", nullable=true)
     */
    private $inicioHora;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="time", nullable=true)
     */
    private $finHora;
    /**
     *@ORM\ManyToOne(targetEntity="EtiquetaAgenda")
     *@ORM\JoinColumn(name="etiqueta", referencedColumnName="id")
     */
    private $etiqueta;
    
    
    /**
     *
     * @ORM\ManyToOne (targetEntity="Usuario", inversedBy="agendas")
     */
    private $usuario;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    protected $visto;
    

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
     * Set asunto
     *
     * @param string $asunto
     * @return Agenda
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    
        return $this;
    }

    /**
     * Get asunto
     *
     * @return string 
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set lugar
     *
     * @param string $lugar
     * @return Agenda
     */
    public function setLugar($lugar)
    {
        $this->lugar = $lugar;
    
        return $this;
    }

    /**
     * Get lugar
     *
     * @return string 
     */
    public function getLugar()
    {
        return $this->lugar;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Agenda
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
     * Set todos
     *
     * @param boolean $todos
     * @return Agenda
     */
    public function setTodos($todos)
    {
        $this->todos = $todos;
    
        return $this;
    }

    /**
     * Get todos
     *
     * @return boolean 
     */
    public function getTodos()
    {
        return $this->todos;
    }

    /**
     * Set inicioFec
     *
     * @param \DateTime $inicioFec
     * @return Agenda
     */
    public function setInicioFec($inicioFec)
    {
        $this->inicioFec = $inicioFec;
    
        return $this;
    }

    /**
     * Get inicioFec
     *
     * @return \DateTime 
     */
    public function getInicioFec()
    {
        return $this->inicioFec;
    }

    /**
     * Set finFec
     *
     * @param \DateTime $finFec
     * @return Agenda
     */
    public function setFinFec($finFec)
    {
        $this->finFec = $finFec;
    
        return $this;
    }

    /**
     * Get finFec
     *
     * @return \DateTime 
     */
    public function getFinFec()
    {
        return $this->finFec;
    }

    /**
     * Set inicioHora
     *
     * @param \DateTime $inicioHora
     * @return Agenda
     */
    public function setInicioHora($inicioHora)
    {
        $this->inicioHora = $inicioHora;
    
        return $this;
    }

    /**
     * Get inicioHora
     *
     * @return \DateTime 
     */
    public function getInicioHora()
    {
        return $this->inicioHora;
    }

    /**
     * Set finHora
     *
     * @param \DateTime $finHora
     * @return Agenda
     */
    public function setFinHora($finHora)
    {
        $this->finHora = $finHora;
    
        return $this;
    }

    /**
     * Get finHora
     *
     * @return \DateTime 
     */
    public function getFinHora()
    {
        return $this->finHora;
    }

    /**
     * Set etiqueta
     *
     * @param \SisGG\FinalBundle\Entity\EtiquetaAgenda $etiqueta
     * @return Agenda
     */
    public function setEtiqueta(\SisGG\FinalBundle\Entity\EtiquetaAgenda $etiqueta = null)
    {
        $this->etiqueta = $etiqueta;
    
        return $this;
    }

    /**
     * Get etiqueta
     *
     * @return \SisGG\FinalBundle\Entity\EtiquetaAgenda 
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Agenda
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
     * Set visto
     *
     * @param boolean $visto
     * @return Agenda
     */
    public function setVisto($visto)
    {
        $this->visto = $visto;
    
        return $this;
    }

    /**
     * Get visto
     *
     * @return boolean 
     */
    public function getVisto()
    {
        return $this->visto;
    }
    public function getTodoeldia() {
        if ($this->todos){
            return "Si";
        }else{
            return "No";
        }
    }
}