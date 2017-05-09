<?php

namespace SisGG\FinalBundle\Model;

use Doctrine\ORM\EntityManager;
use SisGG\FinalBundle\Model\GestorMensajes;
use SisGG\FinalBundle\Entity\CuentaCorriente;
use Doctrine\Common\Collections\ArrayCollection;
use SisGG\FinalBundle\Entity\Cliente;
use SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteDebe;
use SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteHaber;
use SisGG\FinalBundle\Entity\Cobro;
use SisGG\FinalBundle\Entity\Recibo;

/**
 * Description of GestorCuentasCorriente
 *
 * @author martin
 */
class GestorCuentasCorriente {
    /* @var $em EntityManager */

    private $em = null;
    /* @var $gestor GestorMensajes */
    private $gestorMensajes = null;
    /* @var $gestor \SisGG\FinalBundle\Model\GestorCajas */
    private $gestorCajas = null;

     public function __construct(EntityManager $entityManager, GestorMensajes $gestorMensajes, GestorCajas $gestorCajas) {
        $this->em = $entityManager;
        $this->gestorMensajes = $gestorMensajes;
        $this->gestorCajas = $gestorCajas;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->em;
    }

    /**
     * @param integer $id
     * @return CuentaCorriente
     */
    public function getCuentaCorriente($id) {
        $cuentaCorriente = $this->getEntityManager()->getRepository("SisGGFinalBundle:CuentaCorriente")->find($id);
        return $cuentaCorriente;
    }

    public function getCuentasCorrientes() {
        $cuentaCorriente = $this->getEntityManager()->getRepository("SisGGFinalBundle:CuentaCorriente")->findAll();
        return $cuentaCorriente;
    }

    public function getCuentasCorrientesActivas() {
        $resultado = $this->getEntityManager()->getRepository("SisGGFinalBundle:CuentaCorriente")->findAll();
        $cuentasCorrientes = array();
        foreach ($resultado as $cuentaCorriente) {
            if ($cuentaCorriente->getEstado() == 'activo') {
                $cuentasCorrientes[] = $cuentaCorriente;
            }
        }
        return $cuentasCorrientes;
    }

    public function getNúmeroCuentaSiguiente() {
        $resultado = $this->getEntityManager()->getRepository("SisGGFinalBundle:CuentaCorriente")->findAll();
        $numeroCuenta = null;
        if ($resultado != null) {
            $numeroCuenta = end($resultado)->getNumeroCuenta() + 1;
        } else {
            $numeroCuenta = 1;
        }
        return $numeroCuenta;
    }

    public function nuevaCuentaCorriente($id = null) {
        $unaCuentaCorriente = new CuentaCorriente();
        $unaCuentaCorriente->setNumeroCuenta($this->getNúmeroCuentaSiguiente());
        if ($id != null) {
            $unaCuentaCorriente->setCliente($this->em->getRepository("SisGGFinalBundle:Cliente")->find($id));
        }
        return $unaCuentaCorriente;
    }
    
    public function nuevoMovimientoHaber($id=null){
        $unaCuentaCorriente = $this->getCuentaCorriente($id);
        $unMovimientoHaber = new MovimientoCuentaCorrienteHaber();
        $unMovimientoHaber->setCuenta($unaCuentaCorriente);
        $unCobro = new Cobro();
        $unCobro->setTipoCobro($this->getEntityManager()->getRepository("SisGGFinalBundle:TipoCobro")->findAll()[0]);
        $unCobro->setImporte($unaCuentaCorriente->getSaldo() * -1);
        $unMovimientoHaber->setUnCobro($unCobro);
        return $unMovimientoHaber;
    }

    public function registrarMovimientoDebe(\SisGG\FinalBundle\Entity\Venta $unaVenta = null) {
        if ($unaVenta->getEstado() != 'Cobrado') {
            $unMovimientoDebe = new MovimientoCuentaCorrienteDebe();
            $unMovimientoDebe->setUnaVenta($unaVenta);
            $unMovimientoDebe->setCuenta($unaVenta->getCliente()->getCuenta());
            $this->getEntityManager()->persist($unMovimientoDebe);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param integer $id
     * @return CuentaCorriente
     */
    public function getCuentaCorrienteCliente($id) {
        /* @var $unCliente Cliente */
        $unCliente = $this->getEntityManager()->getRepository("SisGGFinalBundle:Cliente")->find($id);
        $cuentaCorriente = $unCliente->getCuenta();
        return $cuentaCorriente;
    }
    
    public function nuevoRecibo($id = null){
        $unaCuentaCorriente = $this->getCuentaCorriente($id);
        $unRecibo = new Recibo();
        $unRecibo->setCliente($unaCuentaCorriente->getCliente());
        $unRecibo->setFecha(new \DateTime('now'));
        $unRecibo->setTotal($unaCuentaCorriente->getSaldo()*-1);
        $unRecibo->setSerie($this->gestorCajas->getNumeroSiguienteRecibo()['serie']);
        $unRecibo->setNumero($this->gestorCajas->getNumeroSiguienteRecibo()['numero']);
        return $unRecibo;
    }
    
    public function guardarRecibo(Recibo $unRecibo,$unUsuario){
        $this->gestorCajas->registrarRecibo($unRecibo, $unUsuario);
        $unMovimientoHaber = new MovimientoCuentaCorrienteHaber();
        $unMovimientoHaber->setUnRecibo($unRecibo);
        $unRecibo->setMovimientoCuentaCorriente($unMovimientoHaber);
        $unMovimientoHaber->setCuenta($unRecibo->getCliente()->getCuenta());
        $unRecibo->setUsuario($unUsuario);
        $this->getEntityManager()->persist($unRecibo);
        $this->getEntityManager()->persist($unMovimientoHaber);
        $this->getEntityManager()->flush();
    }

}

?>
