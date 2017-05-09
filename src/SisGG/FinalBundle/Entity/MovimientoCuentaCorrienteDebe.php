<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Validator\Constraints as MyAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\InheritanceType("JOINED")
 * @Gedmo\Loggable
 */
class MovimientoCuentaCorrienteDebe extends MovimientoCuentaCorriente implements \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Venta", inversedBy="movimientoCuentaCorriente")
     * @Gedmo\Versioned
     */
    protected $unaVenta;


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
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->setEstado("activo");
        $this->setFecha(new \DateTime('now'));
    }

    /**
     * Set cuenta
     *
     * @param \SisGG\FinalBundle\Entity\CuentaCorriente $cuenta
     * @return MovimientoCuentaCorriente
     */
    public function setCuenta(\SisGG\FinalBundle\Entity\CuentaCorriente $cuenta = null) {
        parent::setCuenta($cuenta);
        $cuenta->setSaldo($cuenta->getSaldo() - $this->getImporte());
        return $this;
    }

    public function isDebe(){
        return true;
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
     * Set unaVenta
     *
     * @param \SisGG\FinalBundle\Entity\Venta $unaVenta
     * @return MovimientoCuentaCorrienteDebe
     */
    public function setUnaVenta(\SisGG\FinalBundle\Entity\Venta $unaVenta = null)
    {
        $this->unaVenta = $unaVenta;
        $cobrado = 0.00;
        /* @var $unCobro Cobro*/
        foreach ($unaVenta->getCobros() as $unCobro) {
            $cobrado = $cobrado + $unCobro->getImporte();
        }
        $saldo = $unaVenta->getTotal() - $cobrado;
        $this->setImporte($saldo);
        return $this;
    }

    /**
     * Get unaVenta
     *
     * @return \SisGG\FinalBundle\Entity\Venta 
     */
    public function getUnaVenta()
    {
        return $this->unaVenta;
    }
}