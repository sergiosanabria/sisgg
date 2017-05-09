<?php

namespace SisGG\FinalBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class ContadoEmpleado extends EgresoEmpleado implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $liquido;

    /**
     * @ORM\OneToOne(targetEntity="Pago", inversedBy="contado",cascade={"persist"})
     * @Gedmo\Versioned
     * */
    protected $pago;

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

    public function isContadoEmpleado() {
        return true;
    }

    /**
     * Set liquido
     *
     * @param boolean $liquido
     * @return ContadoEmpleado
     */
    public function setLiquido($liquido) {
        $this->liquido = $liquido;

        return $this;
    }

    /**
     * Get liquido
     *
     * @return boolean 
     */
    public function getLiquido() {
        return $this->liquido;
    }

    public function pagar(Usuario $usuario) {
        $hoy = new \DateTime('now');
        /* @var $empresa Empresa */
        $empresa = $this->getCuenta()->getEmpleado()->getEmpresa();
        if ($this->liquido) {
            if ($empresa->estaCajaAbierta()) {
                $pago = new Pago();
                $pago->setAclaracion('Pago a empleado');
                $pago->setContado($this);
                $pago->setImporte($this->getMonto());
                $pago->setFecha($hoy);
                $pago->setFechaEmision();
                $this->setPago($pago);
                /* @var $tipocobro TipoCobro */
                $tipocobro = $empresa->obtenerTipoCobro('Efectivo registrable');
                $pago->setTipoCobro($tipocobro);
                $pago->crearSalida($usuario, $empresa);
                return false;
            } else {
                return true;
            }
        } else {
            $pago = new Pago();
            $pago->setAclaracion('Pago a empleado');
            $pago->setContado($this);
            $pago->setFecha($hoy);
            $pago->setFechaEmision();
            $pago->setImporte($this->getMonto());
            /* @var $tipocobro TipoCobro */
            $tipocobro = $empresa->obtenerTipoCobro('Efectivo no registrable');
            $pago->setTipoCobro($tipocobro);
            $this->setPago($pago);
            return false;
        }
    }

    /**
     * Set pago
     *
     * @param \SisGG\FinalBundle\Entity\Pago $pago
     * @return ContadoEmpleado
     */
    public function setPago(\SisGG\FinalBundle\Entity\Pago $pago = null) {
        $this->pago = $pago;

        return $this;
    }

    /**
     * Get pago
     *
     * @return \SisGG\FinalBundle\Entity\Pago 
     */
    public function getPago() {
        return $this->pago;
    }

}