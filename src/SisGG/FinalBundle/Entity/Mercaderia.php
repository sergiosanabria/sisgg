<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\ProductoVenta;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Mercaderia extends ProductoVenta implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descripcion;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $precioVenta;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $precioCosto;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $cantidad;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $margen;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $margenMin;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $supera;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $superaMin;
    
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $cantidadMinima;
    /**
     * @ORM\ManyToOne(targetEntity="Proveedor",inversedBy="mercaderias")
     */
    protected $proveedor;

    /**
     * @ORM\OneToOne(targetEntity="Image",cascade={"persist"})
     * @ORM\JoinColumn(name="imagen_id", referencedColumnName="id", nullable=true)
     */
    protected $foto;

    /**
     * @ORM\ManyToOne(targetEntity="IVA")
     */
    protected $iva;

    /**
     * @ORM\ManyToOne(targetEntity="Tasa",inversedBy="productosVenta")
     */
    protected $tasa;
    
    /**
     * @return boolean
     */
    public function isMercaderia() {
        return true;
    }

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
     * @return type
     */
    public function getCantidadDisponible() {
        return $this->getCantidad();
    }

    public function toArray(){
        $retorno = array();
        $retorno ['id'] = $this->id;
        $retorno ['nombre'] = $this->nombre;
        $retorno ['precioVenta'] = $this->precioVenta;
        $retorno ['iva'] = $this->iva->getId();
        return $retorno;
    }
    
    /**
     * Verifica si es posible realizar la cantidad especificada, en caso
     * de que no se pueda devuelve un array con los faltantes obligatorios
     */
    public function comprobarDisponibilidad(&$productosUtilizados,$cantidadAElaborar,$ingredientes){
        $faltantes = array(); // variable para determinar los faltantes 
        return $faltantes;
    }
    
    
    
    /**
     * Descuenta del Stock
     */
    public function descontar($cantidadNecesaria, $ingredientes = null, &$superados){
        $cantidad = $cantidadNecesaria/$this->getTasa()->getValor();
        $this->setCantidad($cantidad < $this->getCantidad() ? $this->getCantidad() - $cantidad : 0.00 );
        $superados = $superados | $this->isSuperaMin();
    }
    
    public function isMargenSuperior(){
        if ($this->margen==null)
            $this->margen=0.00;
        if ($this->margenMin==null)
            $this->margenMin=0.00;
        if ($this->margenMin > $this->margen){
            $this->supera=true;
            return true;
        }
        $this->supera=false;
        return false;
    }
    public function isSuperaMin(){
        if ($this->cantidad==null)
            $this->cantidad=0.00;
        if ($this->cantidadMinima==null)
            $this->cantidadMinima=0.00;
        if ($this->cantidadMinima > $this->cantidad){
            $this->superaMin=true;
            return true;
        }
        $this->superaMin=false;
        return false;
    }
    
    public function getIngredientes(){
        $retorno = array();
        return $retorno;
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
     * @var string $nombre
     */
    protected $nombre;

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Mercaderia
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad() {
        return $this->cantidad;
    }


    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Mercaderia
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
     * Set precioVenta
     *
     * @param float $precioVenta
     * @return Mercaderia
     */
    public function setPrecioVenta($precioVenta)
    {
        $this->precioVenta = $precioVenta;
    
        return $this;
    }

    /**
     * Get precioVenta
     *
     * @return float 
     */
    public function getPrecioVenta()
    {
        return $this->precioVenta;
    }

    /**
     * Set precioCosto
     *
     * @param float $precioCosto
     * @return Mercaderia
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
     * Set margen
     *
     * @param float $margen
     * @return Mercaderia
     */
    public function setMargen($margen)
    {
        $this->margen = $margen;
    
        return $this;
    }

    /**
     * Get margen
     *
     * @return float 
     */
    public function getMargen()
    {
        return $this->margen;
    }

    /**
     * Set margenMin
     *
     * @param float $margenMin
     * @return Mercaderia
     */
    public function setMargenMin($margenMin)
    {
        $this->margenMin = $margenMin;
    
        return $this;
    }

    /**
     * Get margenMin
     *
     * @return float 
     */
    public function getMargenMin()
    {
        return $this->margenMin;
    }

    /**
     * Set supera
     *
     * @param boolean $supera
     * @return Mercaderia
     */
    public function setSupera($supera)
    {
        $this->supera = $supera;
    
        return $this;
    }

    /**
     * Get supera
     *
     * @return boolean 
     */
    public function getSupera()
    {
        return $this->supera;
    }

    /**
     * Set cantidadDeseada
     *
     * @param float $cantidadDeseada
     * @return Mercaderia
     */
    public function setCantidadDeseada($cantidadDeseada)
    {
        $this->cantidadDeseada = $cantidadDeseada;
    
        return $this;
    }

    /**
     * Get cantidadDeseada
     *
     * @return float 
     */
    public function getCantidadDeseada()
    {
        return $this->cantidadDeseada;
    }

    /**
     * Set cantidadMinima
     *
     * @param float $cantidadMinima
     * @return Mercaderia
     */
    public function setCantidadMinima($cantidadMinima)
    {
        $this->cantidadMinima = $cantidadMinima;
    
        return $this;
    }

    /**
     * Get cantidadMinima
     *
     * @return float 
     */
    public function getCantidadMinima()
    {
        return $this->cantidadMinima;
    }

    /**
     * Set proveedor
     *
     * @param SisGG\FinalBundle\Entity\Proveedor $proveedor
     * @return Mercaderia
     */
    public function setProveedor(\SisGG\FinalBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return SisGG\FinalBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }


    /**
     * Set iva
     *
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     * @return Mercaderia
     */
    public function setIva(\SisGG\FinalBundle\Entity\IVA $iva = null)
    {
        $this->iva = $iva;
    
        return $this;
    }

    /**
     * Get iva
     *
     * @return \SisGG\FinalBundle\Entity\IVA 
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     * @return Mercaderia
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }



    /**
     * Set foto
     *
     * @param \SisGG\FinalBundle\Entity\Image $foto
     * @return Mercaderia
     */
    public function setFoto(\SisGG\FinalBundle\Entity\Image $foto = null)
    {
        $this->foto = $foto;
    
        return $this;
    }

    /**
     * Get foto
     *
     * @return \SisGG\FinalBundle\Entity\Image 
     */
    public function getFoto()
    {
        return $this->foto;
    }
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $descuentos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->descuentos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add descuentos
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos
     * @return Mercaderia
     */
    public function addDescuento(\SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos)
    {
        $this->descuentos[] = $descuentos;
    
        return $this;
    }

    /**
     * Remove descuentos
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos
     */
    public function removeDescuento(\SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos)
    {
        $this->descuentos->removeElement($descuentos);
    }

    /**
     * Get descuentos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescuentos()
    {
        return $this->descuentos;
    }

    /**
     * Set superaMin
     *
     * @param boolean $superaMin
     * @return Mercaderia
     */
    public function setSuperaMin($superaMin)
    {
        $this->superaMin = $superaMin;
    
        return $this;
    }

    /**
     * Get superaMin
     *
     * @return boolean 
     */
    public function getSuperaMin()
    {
        return $this->superaMin;
    }

    /**
     * Set tasa
     *
     * @param \SisGG\FinalBundle\Entity\Tasa $tasa
     * @return Mercaderia
     */
    public function setTasa(\SisGG\FinalBundle\Entity\Tasa $tasa = null)
    {
        $this->tasa = $tasa;
    
        return $this;
    }

    /**
     * Get tasa
     *
     * @return \SisGG\FinalBundle\Entity\Tasa 
     */
    public function getTasa()
    {
        return $this->tasa;
    }
}