<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
use SisGG\FinalBundle\Entity\ProductoProduccion;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class PreElaborado extends ProductoProduccion implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $descripcion;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $precioCosto;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $cantidad;

    /**
     * @ORM\Column(type="boolean")
     */
    private $superaMin;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $cantidadMinima;

    /**
     * @ORM\ManyToOne(targetEntity="Tasa")
     * @ORM\JoinColumn(name="tasa_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $tasa;

    /**
     * @ORM\OneToMany(targetEntity="Ingrediente",mappedBy="preElaborado", cascade={"persist"})
     */
    protected $ingredientes;
    
    private $conIng;

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
     * @var string $nombre
     */
    protected $nombre;

    public function getCantidadDisponible() {
        parent::getCantidadDisponible();
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
     * Set nombre
     *
     * @param string $nombre
     * @return PreElaborado
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
     * Set ingredientes
     *
     * @param SisGG\FinalBundle\Entity\Ingrediente $ingredientes
     * @return PreElaborado
     */
    public function setIngredientes(\SisGG\FinalBundle\Entity\Ingrediente $ingredientes = null) {
        $this->ingredientes = $ingredientes;

        return $this;
    }

    /**
     * Get ingredientes
     *
     * @return SisGG\FinalBundle\Entity\Ingrediente 
     */
    public function getIngredientes() {
        return $this->ingredientes;
    }


    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return PreElaborado
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }
    
    //Actualiza el precio y los ceoficientes de los ingredientes y su precio total
    public function actualizarIngredientes() {
        foreach ($this->getIngredientes() as $value) {
            $value->calcularCoeficiente();
        }
        $this->sumarCosto();
    }
    
    public function isPreElaborado() {
        return true;
    }
 

    //Verifica si el producto pre-elaborado contiene o se repite un un ingrediente con ese producto produccion
    public function existeIngrediente(Ingrediente $ingrediente, $tipo = null) {
        foreach ($this->getIngredientes() as $value) {
            If ($ingrediente->getProductoProduccion() == $value->getProductoProduccion()) {
                if ($tipo != null) {
                    return true;
                }
                if (($ingrediente->getId() != $value->getId()))
                    return true;
            }
        }
        return false;
    }

    public function sumarCosto() {
        $total = 0.00;
        $costo = 0.00;
        foreach ($this->getIngredientes() as $value) {
            $costo = $value->getProductoProduccion()->getPrecioCosto() * $value->getCoeficiente() * $value->getCantidad();
            $value->calcularPrecioCosto($costo);
            $total = $costo + $total;
        }
        $this->setPrecioCosto($total);
//        if (($this->getIngredientes()->isEmpty())) {
//            $total=(($this->getIngredientes()->getProductoProduccion()->getPrecioCosto() * $this->getIngredientes()->getCoeficiente()));
//            $this->setPrecioCosto($total);
//        }
    }

    public function registrarProduccion1($cantidad, $array) {
        $set = 0.00;
        $costo=0.00;
        $i = 0;
        $nuevoRegistro = new RegistroProduccion ();
        foreach ($this->getIngredientes() as $value) {
            if ($array[$i] == 1) {
                $set = $value->getProductoProduccion()->getCantidad() - ($cantidad * $value->getCoeficiente() * $value->getCantidad());
                if ($set >= 0.00) {
                    $c=0.00;
                    $c=$cantidad * $value->getCantidad();
                    $co=0.00;
                    $co=($c *$value->getProductoProduccion()->getPrecioCosto()* $value->getCoeficiente());
                    $costo+=$co;
                    $nuevoRegistro->agregarItem($value->getProductoProduccion(), $c,$co,$value->getTasa(),true);
                    $value->getProductoProduccion()->setCantidad($set);
                    
                    
                    
                } else {
                     $c=0.00;
                     $c=$value->getProductoProduccion()->getCantidad()*($value->getProductoProduccion()->getTasa()->getValor()/$value->getTasa()->getValor());
                    $co=0.00;
                    $co=($c *$value->getProductoProduccion()->getPrecioCosto()* $value->getCoeficiente());
                    $costo+=$co;
                     $nuevoRegistro->agregarItem($value->getProductoProduccion(),$c , $co,$value->getTasa(),true);
                    $value->getProductoProduccion()->setCantidad(0.00);
                }
            }else{
                $nuevoRegistro->agregarItem($value->getProductoProduccion(), 0.00,0.00,$value->getTasa(),false);
            }
            $i++;
        }
        $nuevoRegistro->setCantidad($cantidad);
        $nuevoRegistro->setProducto($this);
        $nuevoRegistro->setFecha();
        $nuevoRegistro->setCosto($costo);
        $nuevoRegistro->setTasa($this->getTasa());
        $this->setCantidad($this->cantidad+$cantidad);
        return $nuevoRegistro;
    }
   
    public function perdidaProduccion1($cantidad, $array) {
        $set = 0.00;
        $costo=0.00;
        $i = 0;
        $nuevoRegistro = new RegistroProduccion ();
        foreach ($this->getIngredientes() as $value) {
            if ($array[$i] == 1) {
                $set = $value->getProductoProduccion()->getCantidad() - ($cantidad * $value->getCoeficiente() * $value->getCantidad());
                if ($set >= 0.00) {
                    $c=0.00;
                    $c=$cantidad * $value->getCantidad();
                    $co=0.00;
                    $co=($c *$value->getProductoProduccion()->getPrecioCosto()* $value->getCoeficiente());
                    $costo+=$co;
                    $nuevoRegistro->agregarItem($value->getProductoProduccion(), $c,$co,$value->getTasa(),true);
                    $value->getProductoProduccion()->setCantidad($set);
                    
                    
                    
                } else {
                     $c=0.00;
                     $c=$value->getProductoProduccion()->getCantidad()*($value->getProductoProduccion()->getTasa()->getValor()/$value->getTasa()->getValor());
                    $co=0.00;
                    $co=($c *$value->getProductoProduccion()->getPrecioCosto()* $value->getCoeficiente());
                    $costo+=$co;
                     $nuevoRegistro->agregarItem($value->getProductoProduccion(),$c , $co,$value->getTasa(),true);
                    $value->getProductoProduccion()->setCantidad(0.00);
                }
            }else{
                $nuevoRegistro->agregarItem($value->getProductoProduccion(), 0.00,0.00,$value->getTasa(),false);
            }
            $i++;
        }
        $nuevoRegistro->setCantidad($cantidad);
        $nuevoRegistro->setProducto($this);
        $nuevoRegistro->setFecha();
        $nuevoRegistro->setCosto($costo);
        $nuevoRegistro->setTasa($this->getTasa());
        $this->setCantidad($this->cantidad+$cantidad);
        return $nuevoRegistro;
    }

    public function registrarProduccion2($cantidad) {
        $set = 0.00;
        foreach ($this->getIngredientes() as $value) {
            $set = $value->getProductoProduccion()->getCantidad() - ($cantidad * $value->getCoeficiente() * $value->getCantidad());
            if ($set > 0.00) {
                $value->getProductoProduccion()->setCantidad($set);
            } else {
                $value->getProductoProduccion()->setCantidad(0.00);
            }
        }
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


    /**
     * Set iva
     *
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     * @return PreElaborado
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
     * Constructor
     */
    public function __construct()
    {
        $this->ingredientes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return PreElaborado
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
     * Set precioCosto
     *
     * @param string $precioCosto
     * @return PreElaborado
     */
    public function setPrecioCosto($precioCosto)
    {
        $this->precioCosto = $precioCosto;
    
        return $this;
    }

    /**
     * Get precioCosto
     *
     * @return string 
     */
    public function getPrecioCosto()
    {
        return $this->precioCosto;
    }

    /**
     * Set superaMin
     *
     * @param boolean $superaMin
     * @return PreElaborado
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
     * Set cantidadMinima
     *
     * @param string $cantidadMinima
     * @return PreElaborado
     */
    public function setCantidadMinima($cantidadMinima)
    {
        $this->cantidadMinima = $cantidadMinima;
    
        return $this;
    }

    /**
     * Get cantidadMinima
     *
     * @return string 
     */
    public function getCantidadMinima()
    {
        return $this->cantidadMinima;
    }

    /**
     * Set tasa
     *
     * @param \SisGG\FinalBundle\Entity\Tasa $tasa
     * @return PreElaborado
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

    /**
     * Add ingredientes
     *
     * @param \SisGG\FinalBundle\Entity\Ingrediente $ingredientes
     * @return PreElaborado
     */
    public function addIngrediente(\SisGG\FinalBundle\Entity\Ingrediente $ingredientes)
    {
        $this->ingredientes[] = $ingredientes;
    
        return $this;
    }

    /**
     * Remove ingredientes
     *
     * @param \SisGG\FinalBundle\Entity\Ingrediente $ingredientes
     */
    public function removeIngrediente(\SisGG\FinalBundle\Entity\Ingrediente $ingredientes)
    {
        $this->ingredientes->removeElement($ingredientes);
    }
    public function getConIng() {
        return $this->conIng;
    }

    public function setConIng($conIng) {
        $this->conIng = $conIng;
    }


    
}