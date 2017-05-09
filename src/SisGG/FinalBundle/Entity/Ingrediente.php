<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Entity\ProductoProduccion;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Ingrediente implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $cantidad;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $precioCosto;
    /**
     * @ORM\Column(type="decimal", scale=6, nullable=true)
     */
    private $coeficiente;
    /**
     * @ORM\Column(type="boolean")
     */
    private $obligatorio;
    /**
     * @ORM\ManyToOne(targetEntity="Tasa")
     * @ORM\JoinColumn(name="tasa_id", referencedColumnName="id")
     */
    private $tasa;
    /**
     * @ORM\ManyToOne(targetEntity="ProductoProduccion")
     * @ORM\JoinColumn(name="productoproduccion_id", referencedColumnName="id", nullable=false)
     */
    private $productoProduccion;
    /**
     * @ORM\ManyToOne(targetEntity="PreElaborado",inversedBy="ingredientes", cascade={"persist"})
     */
    private $preElaborado;
      /**
     * @ORM\ManyToOne(targetEntity="Plato",inversedBy="ingredientes", cascade={"persist"})
     */
    private $plato;
    
    public function getCantidadDisponible() {
        $retorno = ((($this->getProductoProduccion()->getTasa()->getValor()) / ($this->getTasa()->getValor())) * $this->getProductoProduccion()->getCantidad())/$this->getCantidad();
        return $retorno;
    }
    
    public function getObligatoriedad(){
        $retorno = "No Obligatorio";
        if ($this->obligatorio==true){
            $retorno = "Obligatorio";
        }
        return $retorno;
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
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     * @return Ingrediente
     */
    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float 
     */
    public function getCantidad() {
        return $this->cantidad;
    }

    /**
     * Set productoProduccion
     *
     * @param SisGG\FinalBundle\Entity\ProductoProduccion $productoProduccion
     * @return Ingrediente
     */
    public function setProductoProduccion(\SisGG\FinalBundle\Entity\ProductoProduccion $productoProduccion = null) {
        $this->productoProduccion = $productoProduccion;

        return $this;
    }

    /**
     * Get productoProduccion
     *
     * @return SisGG\FinalBundle\Entity\ProductoProduccion 
     */
    public function getProductoProduccion() {
        return $this->productoProduccion;
    }

    /**
     * Set tasa
     *
     * @param SisGG\FinalBundle\Entity\Tasa $tasa
     * @return Ingrediente
     */
    public function setTasa(\SisGG\FinalBundle\Entity\Tasa $tasa = null) {
        $this->tasa = $tasa;

        return $this;
    }

    /**
     * Get tasa
     *
     * @return SisGG\FinalBundle\Entity\Tasa 
     */
    public function getTasa() {
        return $this->tasa;
    }

    /**
     * Set preElaborado
     *
     * @param SisGG\FinalBundle\Entity\PreElaborado $preElaborado
     * @return Ingrediente
     */
    public function setPreElaborado(\SisGG\FinalBundle\Entity\PreElaborado $preElaborado = null) {
        $this->preElaborado = $preElaborado;

        return $this;
    }

    /**
     * Get preElaborado
     *
     * @return SisGG\FinalBundle\Entity\PreElaborado 
     */
    public function getPreElaborado() {
        return $this->preElaborado;
    }

    /**
     * Set coeficiente
     *
     * @param float $coeficiente
     * @return Ingrediente
     */
    public function setCoeficiente($coeficiente) {
        $this->coeficiente = $coeficiente;

        return $this;
    }

    /**
     * Get coeficiente
     *
     * @return float 
     */
    public function getCoeficiente() {
        return $this->coeficiente;
    }

    public function calcularCoeficiente() {
        $valor=0.00;
        if ($this->getProductoProduccion()->getTasa()->getValor() != 0) {
            $valor = $this->getTasa()->getValor() / $this->getProductoProduccion()->getTasa()->getValor();
            $this->setCoeficiente($valor);
        }
    }
    
    public function calcularPrecioCosto (){
        $costo=0.00;
        $costo=$this->getProductoProduccion()->getPrecioCosto() * $this->getCoeficiente()*$this->getCantidad();
        $this->setPrecioCosto($costo);
    }

    /**
     * Set precioCosto
     *
     * @param float $precioCosto
     * @return Ingrediente
     */
    public function setPrecioCosto($precioCosto)
    {
        $this->precioCosto = $precioCosto;
    
        return $this;
    }

    /**
     * Get precioCosto
     *
     * @return float 
     */
    public function getPrecioCosto()
    {
        return $this->precioCosto;
    }

    /**
     * Set plato
     *
     * @param SisGG\FinalBundle\Entity\Plato $plato
     * @return Ingrediente
     */
    public function setPlato(\SisGG\FinalBundle\Entity\Plato $plato = null)
    {
        $this->plato = $plato;
    
        return $this;
    }

    /**
     * Get plato
     *
     * @return SisGG\FinalBundle\Entity\Plato 
     */
    public function getPlato()
    {
        return $this->plato;
    }
   


    /**
     * Set obligatorio
     *
     * @param boolean $obligatorio
     * @return Ingrediente
     */
    public function setObligatorio($obligatorio)
    {
        $this->obligatorio = $obligatorio;
    
        return $this;
    }

    /**
     * Get obligatorio
     *
     * @return boolean 
     */
    public function getObligatorio()
    {
        return $this->obligatorio;
    }
    
    public function getExclusion() {
        if ($this->preElaborado!=null){
            return "Sin ".$this->preElaborado;
        }
        if ($this->productoProduccion!=null){
            return "Sin ".$this->productoProduccion;
        }
    }
}