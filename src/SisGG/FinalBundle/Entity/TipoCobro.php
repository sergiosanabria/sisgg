<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\TipoCobroRepository")
 * @Gedmo\Loggable
 * @UniqueEntity(fields="nombre", message="Ya existe un tipo de cobro/pago con este nombre.")
 */
class TipoCobro implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @Assert\NotBlank(message="Ingrese el nombre.")
     * @ORM\Column(type="string")
     */
    protected $nombre;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $descripcion;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $liquido; //Si se registra o no en caja como entrada
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $editable; //Si el tipo de pago se puede editar o eliminar
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $darCambio;
    /**
      * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $montoMinimo;
    /**
      * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $montoMaximo;
    
    /**
     * @ORM\OneToMany(targetEntity="Campo",mappedBy="tipoCobro",cascade={"all"})
     */
    protected $campos;
    /**
     * @ORM\OneToMany(targetEntity="Cobro",mappedBy="tipoCobro")
     */
    protected $cobros;
    /**
     * @ORM\OneToMany(targetEntity="Pago",mappedBy="tipoCobro")
     */
    protected $pagos;
     /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="tiposCobro")
     */
    private $empresa;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $activo;
    
    /**
     * ORM\OneToMany(targetEntity="Cierre",mappedBy="tipoCobro")
     */
    //protected $cierres;
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
        return $this->getNombre();
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
        $this->campos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cobros = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TipoCobro
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
     * @return TipoCobro
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
     * Set liquido
     *
     * @param boolean $liquido
     * @return TipoCobro
     */
    public function setLiquido($liquido)
    {
        $this->liquido = $liquido;
    
        return $this;
    }

    /**
     * Get liquido
     *
     * @return boolean 
     */
    public function getLiquido()
    {
        return $this->liquido;
    }

    /**
     * Set darCambio
     *
     * @param boolean $darCambio
     * @return TipoCobro
     */
    public function setDarCambio($darCambio)
    {
        $this->darCambio = $darCambio;
    
        return $this;
    }

    /**
     * Get darCambio
     *
     * @return boolean 
     */
    public function getDarCambio()
    {
        return $this->darCambio;
    }

    /**
     * Set montoMinimo
     *
     * @param float $montoMinimo
     * @return TipoCobro
     */
    public function setMontoMinimo($montoMinimo)
    {
        $this->montoMinimo = $montoMinimo;
    
        return $this;
    }

    /**
     * Get montoMinimo
     *
     * @return float 
     */
    public function getMontoMinimo()
    {
        return $this->montoMinimo;
    }

    /**
     * Add campos
     *
     * @param \SisGG\FinalBundle\Entity\Campo $campos
     * @return TipoCobro
     */
    public function addCampo(\SisGG\FinalBundle\Entity\Campo $campos)
    {
        $this->campos[] = $campos;
    
        return $this;
    }

    /**
     * Remove campos
     *
     * @param \SisGG\FinalBundle\Entity\Campo $campos
     */
    public function removeCampo(\SisGG\FinalBundle\Entity\Campo $campos)
    {
        $this->campos->removeElement($campos);
    }

    /**
     * Get campos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCampos()
    {
        return $this->campos;
    }

    /**
     * Add cobros
     *
     * @param \SisGG\FinalBundle\Entity\Cobro $cobros
     * @return TipoCobro
     */
    public function addCobro(\SisGG\FinalBundle\Entity\Cobro $cobros)
    {
        $this->cobros[] = $cobros;
    
        return $this;
    }

    /**
     * Remove cobros
     *
     * @param \SisGG\FinalBundle\Entity\Cobro $cobros
     */
    public function removeCobro(\SisGG\FinalBundle\Entity\Cobro $cobros)
    {
        $this->cobros->removeElement($cobros);
    }

    /**
     * Get cobros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCobros()
    {
        return $this->cobros;
    }

    /**
     * Set montoMaximo
     *
     * @param float $montoMaximo
     * @return TipoCobro
     */
    public function setMontoMaximo($montoMaximo)
    {
        $this->montoMaximo = $montoMaximo;
    
        return $this;
    }

    /**
     * Get montoMaximo
     *
     * @return float 
     */
    public function getMontoMaximo()
    {
        return $this->montoMaximo;
    }

    

    /**
     * Add pagos
     *
     * @param \SisGG\FinalBundle\Entity\Pago $pagos
     * @return TipoCobro
     */
    public function addPago(\SisGG\FinalBundle\Entity\Pago $pagos)
    {
        $this->pagos[] = $pagos;
    
        return $this;
    }

    /**
     * Remove pagos
     *
     * @param \SisGG\FinalBundle\Entity\Pago $pagos
     */
    public function removePago(\SisGG\FinalBundle\Entity\Pago $pagos)
    {
        $this->pagos->removeElement($pagos);
    }

    /**
     * Get pagos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPagos()
    {
        return $this->pagos;
    }

    /**
     * Set editable
     *
     * @param boolean $editable
     * @return TipoCobro
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;
    
        return $this;
    }

    /**
     * Get editable
     *
     * @return boolean 
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return TipoCobro
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return \SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return TipoCobro
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }
}