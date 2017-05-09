<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Movimiento;
use SisGG\FinalBundle\Entity\Entrada;
use SisGG\FinalBundle\Entity\Salida;
use SisGG\FinalBundle\Entity\Apertura;
use SisGG\FinalBundle\Entity\TipoCobro;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Lote implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @Gedmo\Versioned
     */
    protected $usuario;
    /**
     * @ORM\ManyToOne(targetEntity="Caja",inversedBy="lotes")
     * @Gedmo\Versioned
     */
    protected $caja;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Gedmo\Versioned
     */
    protected $fechaApertura;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Gedmo\Versioned
     */
    protected $fechaCierre;
    /**
     * @ORM\OneToMany(targetEntity="Movimiento",mappedBy="lote",cascade="persist")
     * @ORM\OrderBy({"fecha" = "ASC"})
     */
    protected $movimientos;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $saldo;
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
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getEntradas(){
        $retorno = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->getTipoMovimiento()=="Entrada"){
                $retorno->add($movimiento);
            }
        }
        return $retorno;
    }
    
    public function getSalidas(){
        $retorno = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->getTipoMovimiento()=="Salida"){
                $retorno->add($movimiento);
            }
        }
        return $retorno;
    }
    
    /**
     * @param TipoCobro $tipo
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getEntradasSalidasTipo($tipo){
        $retorno = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->getTipoMovimiento()=="Entrada"||$movimiento->getTipoMovimiento()=="Salida"){
                if ($movimiento->getTipo()==$tipo){
                    $retorno->add($movimiento);
                }
            }
        }
        return $retorno;
    }
    
    /**
     * @param TipoCobro $tipo
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getEntradasTipo($tipo){
        $retorno = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->getTipoMovimiento()=="Entrada"){
                if ($movimiento->getTipo()==$tipo){
                    $retorno->add($movimiento);
                }
            }
        }
        return $retorno;
    }
    /**
     * @param TipoCobro $tipo
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSalidasTipo($tipo){
        $retorno = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->getTipoMovimiento()=="Salida"){
                if ($movimiento->getTipo()==$tipo){
                    $retorno->add($movimiento);
                }
            }
        }
        return $retorno;
    }
    
    
    public function getTotalEntradasTipo(TipoCobro $tipo){
        $retorno = 0.00;
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->getTipoMovimiento()=="Entrada"){
                if ($movimiento->getTipo()==$tipo){
                    $retorno= $retorno + $movimiento->getImporte();
                }
            }
        }
        return $retorno;
    }
    
    public function getTotalSalidasTipo(TipoCobro $tipo){
        $retorno = 0.00;
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->getTipoMovimiento()=="Salida"){
                if ($movimiento->getTipo()==$tipo){
                    $retorno= $retorno + $movimiento->getImporte();
                }
            }
        }
        return $retorno;
    }
    
    public function getNumeroTransaccionesSalidasTipo(TipoCobro $tipo){
        $retorno = 0;
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->getTipoMovimiento()=="Salida"){
                if ($movimiento->getTipo()==$tipo){
                    $retorno= $retorno + 1;
                }
            }
        }
        return $retorno;
    }
    
    public function getNumeroTransaccionesEntradasTipo(TipoCobro $tipo){
        $retorno = 0;
        foreach ($this->movimientos as $movimiento) {
            if ($movimiento->getTipoMovimiento()=="Entrada"){
                if ($movimiento->getTipo()==$tipo){
                    $retorno= $retorno + 1;
                }
            }
        }
        return $retorno;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fechaApertura
     *
     * @param \DateTime $fechaApertura
     * @return Lote
     */
    public function setFechaApertura($fechaApertura)
    {
        $this->fechaApertura = $fechaApertura;
    
        return $this;
    }

    /**
     * Get fechaApertura
     *
     * @return \DateTime 
     */
    public function getFechaApertura()
    {
        return $this->fechaApertura;
    }

    /**
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     * @return Lote
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;
    
        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime 
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     * @return Lote
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    
        return $this;
    }

    /**
     * Get saldo
     *
     * @return float 
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Lote
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
     * Set caja
     *
     * @param \SisGG\FinalBundle\Entity\Caja $caja
     * @return Lote
     */
    public function setCaja(\SisGG\FinalBundle\Entity\Caja $caja = null)
    {
        $this->caja = $caja;
    
        return $this;
    }

    /**
     * Get caja
     *
     * @return \SisGG\FinalBundle\Entity\Caja 
     */
    public function getCaja()
    {
        return $this->caja;
    }

    /**
     * Add movimientos
     *
     * @param \SisGG\FinalBundle\Entity\Movimiento $movimientos
     * @return Lote
     */
    public function addMovimiento(\SisGG\FinalBundle\Entity\Movimiento $movimientos)
    {
        $this->movimientos[] = $movimientos;
    
        return $this;
    }

    /**
     * Remove movimientos
     *
     * @param \SisGG\FinalBundle\Entity\Movimiento $movimientos
     */
    public function removeMovimiento(\SisGG\FinalBundle\Entity\Movimiento $movimientos)
    {
        $this->movimientos->removeElement($movimientos);
    }

    /**
     * Get movimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovimientos()
    {
        return $this->movimientos;
    }
}