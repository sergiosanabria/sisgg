<?php

namespace SisGG\FinalBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"numero", "serie", "servicio", "periodo"}, message="Factura no válida")
 * @ORM\Table(name="factura_servicio")
 * @Gedmo\Loggable
 */
class FacturaServicio {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Servicio")
     * @ORM\JoinColumn(name="servicio_id", referencedColumnName="id", unique=false)
     */
    private $servicio;

    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $fechaFactura;
    /**
    * @ORM\Column(type="string", nullable=true)
    * @Gedmo\Versioned
    */
    private $periodo;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $fechaEmision;

    /**
      @Assert\Range(
     *      min = 0,
     *      invalidMessage = "No puede ingresar un número menor a 0."
     * )
     * @ORM\Column(type="decimal", scale=3, nullable=true)}
     * @Gedmo\Versioned
     */
    private $total;
     /**
     * @ORM\OneToMany(targetEntity="Pago",mappedBy="facturaServicio", cascade="persist")
     */
    protected $pagos;



    /**
    * @Assert\Range(
     *      min = 1,
     *      max= 9998,
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Assert\NotBlank(message="Ingrese el número de serie.")
     * @ORM\Column(type="string", length=8, nullable=false)
     * @Gedmo\Versioned
     */
    private $serie;

    /**
    * @Assert\Range(
     *      min = 1,
     *      max = 99999999,
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Assert\NotBlank(message="Ingrese el número.")
     * @ORM\Column(type="string", length=8, nullable=false)
     * @Gedmo\Versioned
     */
    private $numero;

    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="facturasServicio")
     */
    private $empresa;

    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set fechaFactura
     *
     * @param \DateTime $fechaFactura
     * @return FacturaServicio
     */
    public function setFechaFactura($fechaFactura) {
        $this->fechaFactura = $fechaFactura;

        return $this;
    }

    /**
     * Get fechaFactura
     *
     * @return \DateTime 
     */
    public function getFechaFactura() {
        return $this->fechaFactura;
    }

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     * @return FacturaServicio
     */
   public function setFechaEmision() {
        $this->fechaEmision =  new \DateTime("now");   
        return $this;

    }

    /**
     * Get fechaEmision
     *
     * @return \DateTime 
     */
    public function getFechaEmision() {
        return $this->fechaEmision;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return FacturaServicio
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal() {
        return $this->total;
    }

   

    /**
     * Set serie
     *
     * @param string $serie
     * @return FacturaServicio
     */
     public function setSerie($serie) {
        $this->serie = str_pad($serie, 4, "0", STR_PAD_LEFT);

        return $this;
    }

    /**
     * Get serie
     *
     * @return string 
     */
    public function getSerie() {
        return $this->serie;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return FacturaServicio
     */
     public function setNumero($numero) {
        
        $this->numero = str_pad($numero, 8, "0", STR_PAD_LEFT);

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero() {
        return $this->numero;
    }


    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return FacturaServicio
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null) {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa() {
        return $this->empresa;
    }

    

    /**
     * Set periodo
     *
     * @param string $periodo
     * @return FacturaServicio
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    
        return $this;
    }

    /**
     * Get periodo
     *
     * @return string 
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }
  

    /**
     * Set servicio
     *
     * @param \SisGG\FinalBundle\Entity\Servicio $servicio
     * @return FacturaServicio
     */
    public function setServicio(\SisGG\FinalBundle\Entity\Servicio $servicio = null)
    {
        $this->servicio = $servicio;
    
        return $this;
    }

    /**
     * Get servicio
     *
     * @return \SisGG\FinalBundle\Entity\Servicio 
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Add pagos
     *
     * @param \SisGG\FinalBundle\Entity\Pago $pagos
     * @return FacturaServicio
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
}