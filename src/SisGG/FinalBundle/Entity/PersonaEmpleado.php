<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use SisGG\FinalBundle\Entity\Usuario;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Description of Usuario
 *
 * @author sergio
 * @ORM\Entity
 * @UniqueEntity(fields="dni", message="Ya existe una persona con este DNI.")
 * @ORM\Table(name="persona_empleado")
 * @Gedmo\Loggable
 */
class PersonaEmpleado extends Usuario implements  \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese un dni")
     * @Assert\Regex(pattern="/\d/",
     *     match=true,
     *     message="El dni solo puede contener números")  
     * @Gedmo\Versioned
     */
    protected $dni;
    /**
     * @ORM\OneToOne(targetEntity="Direccion",cascade={"persist"})
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    protected $direccion;
   
    
    /**
     * @ORM\OneToMany(targetEntity="Telefono",mappedBy="empleado",cascade="persist")
     */
    protected $telefonos;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    protected $fechaNac;

    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    protected $fechaIngreso;

    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $primerPago;

    /**
     * @Assert\Range(
     *      min = "0",
     *      max = "1",
     *      invalidMessage = "El sexo es incorrecto."
     * )
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $sexo;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $activo;

    /**
     * @ORM\OneToOne(targetEntity="CuentaEmpleado", cascade="persist")
     * @ORM\JoinColumn(name="cuenta", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    protected $cuenta;
    
    /**
     * @ORM\OneToMany(targetEntity="CargoEmpleado",mappedBy="empleado")
     */
    protected $cargos;
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="personaEmpleados")
     */
    protected $empresa;

    /**
     * @ORM\OneToOne(targetEntity="Image",cascade={"persist"})
     * @ORM\JoinColumn(name="imagen_id", referencedColumnName="id", nullable=true)
     */
    protected $foto;
   
    
    protected $cargoAct;
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

    public function __toString() {
        return $this->getApellido().', '.$this->getNombre();
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
     * Set fechaNac
     *
     * @param \DateTime $fechaNac
     * @return PersonaEmpleado
     */
    public function setFechaNac($fechaNac) {
        $this->fechaNac = $fechaNac;

        return $this;
    }

    /**
     * Get fechaNac
     *
     * @return \DateTime 
     */
    public function getFechaNac() {
        return $this->fechaNac;
    }

    /**
     * Set sexo
     *
     * @param boolean $sexo
     * @return PersonaEmpleado
     */
    public function setSexo($sexo) {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return boolean 
     */
    public function getSexo() {
        return $this->sexo;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->telefonos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return PersonaEmpleado
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
     * Set cuenta
     *
     * @param \SisGG\FinalBundle\Entity\CuentaEmpleado $cuenta
     * @return PersonaEmpleado
     */
    public function setCuenta(\SisGG\FinalBundle\Entity\CuentaEmpleado $cuenta = null) {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return \SisGG\FinalBundle\Entity\CuentaEmpleado 
     */
    public function getCuenta() {
        return $this->cuenta;
    }

    public function crearCuenta() {
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($this->primerPago == $hoy) {
            $cuenta = new CuentaEmpleado();
            $this->setCuenta($cuenta);
            $cuenta->setSaldo($this->getCargo()->getMonto());
            $cuenta->setUltimoLote($hoy);
            $cuenta->setPendiente(0);
            $newSueldo = new SueldoEmpleado();
            $newSueldo->setMonto($this->getCargo()->getMonto());
            $newSueldo->setDescripcion('Primer pago de sueldo');
            $newSueldo->setFecha($this->primerPago);
            $newSueldo->setInicio($this->fechaIngreso);
            $newSueldo->setFin($this->primerPago);
            $newSueldo->setFechaEmision();
            $newSueldo->setCuenta($cuenta);
            $cuenta->addMovimiento($newSueldo);
            $this->primerPago = null;
        } else {
            $cuenta = new CuentaEmpleado();
            $cuenta->setPendiente(1);
            $cuenta->setSaldo(0.00);
            $cuenta->setUltimoLote(null);
            $this->setCuenta($cuenta);
        }
    }

    public function controlarPago() {
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($this->primerPago != null) {
            if ($hoy >= $this->primerPago) {
                $cuenta = new CuentaEmpleado();
                $cuenta = $this->getCuenta();
                $cuenta->setPendiente(0);
                $cuenta->setSaldo($this->getCargo()->getMonto());
                $cuenta->setUltimoLote($this->primerPago);
                $newSueldo = new SueldoEmpleado();
                $newSueldo->setMonto($this->getCargo()->getMonto());
                if ($this->getCuenta()->getMovimientos() != null) {
                    if (count($this->getCuenta()->getMovimientos()) == 0) {
                        $newSueldo->setDescripcion('Primer pago de sueldo');
                        $newSueldo->setInicio($this->fechaIngreso);
                    } else {
                        $newSueldo->setDescripcion('Primer pago de sueldo después de la modificacion del cargo.');
                        $newSueldo->setInicio($this->getCuenta()->getUltimoLote());
                    }
                } else {
                    $newSueldo->setDescripcion('Primer pago de sueldo');
                    $newSueldo->setInicio($this->fechaIngreso);
                }
                $this->getCuenta()->addMovimiento($newSueldo);
                $newSueldo->setFecha($this->primerPago);
                $newSueldo->setFin($this->primerPago);
                $newSueldo->setFechaEmision();
                $newSueldo->setCuenta($cuenta);
                $cuenta->addMovimiento($newSueldo);
                $this->primerPago = null;
                return 0;
            }
        } else {
            if (($this->getCuenta()->existeFechaSueldoSuperior($hoy))) {
                $this->getCuenta()->setPendiente(0);
                return 3;
            }
            if ((!($this->getCuenta()->existeFechaSueldo($hoy))) && $this->getCuenta()->getUltimoLote() != $hoy) {
                if ($this->getCargo()->getTipo() == 1) {
                    $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                    $mod = $resul->format('%a') / $this->getCargo()->getPorDia();
                    if ($mod >= 1) {
                        $this->getCuenta()->setPendiente(1);
                    } else {
                        $this->getCuenta()->setPendiente(0);
                    }
                    return 1;
                }
                if ($this->getCargo()->getTipo() == 2) {
                    $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                    $dias = $resul->format('%a');
                    if ($dias >= 7) {
                        $this->getCuenta()->setPendiente(1);
                    } else {
                        $this->getCuenta()->setPendiente(0);
                    }
                    return 1;
                }
                if ($this->getCargo()->getTipo() == 0) {
                    $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                    $mes = $resul->format('%m');
                    if ($mes > 0) {
                        $this->getCuenta()->setPendiente(1);
                    } else {
                        $this->getCuenta()->setPendiente(0);
                    }
                    return 1;
                }
            }
        }
        //  date_format($hoy, 'd');
        return -1;
    }

    public function generarListaPendientes() {
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        $array = null;
        if ($this->primerPago == null) {

            if ((!($this->getCuenta()->existeFechaSueldo($hoy))) && (!($this->getCuenta()->existeFechaSueldoSuperior($hoy))) && $this->getCuenta()->getUltimoLote() != $hoy) {
                if ($this->getCuenta()->getUltimoLote() != null) {
                    if ($this->getCargo()->getTipo() == 1) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $div = $resul->format('%a') / $this->getCargo()->getPorDia();
                        if ($div >= 1) {
                            $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $new = new SueldoEmpleado();
                            $new->setInicio($this->getCuenta()->getUltimoLote());
                            $new->setFin($fin->modify('+' . $this->getCargo()->getPorDia() . ' day'));
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            $array[] = $new;
                            for ($i = 1; $i < $this->entero($div); $i++) {
                                $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                                $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                                $new = new SueldoEmpleado();
                                $new->setInicio($ultimo);
                                $new->setFin($fin->modify('+' . $this->getCargo()->getPorDia() . ' day'));
                                $new->setCuenta($this->getCuenta());
                                $new->setDescripcion('Pago de sueldo');
                                $new->setFecha($fin);
                                $new->setMonto($this->getCargo()->getMonto());
                                $array[] = $new;
                            }
                        }
                        return $array;
                    }
                    if ($this->getCargo()->getTipo() == 2) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $dias = $resul->format('%a') / 7;
                        if ($dias >= 1) {
                            $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $new = new SueldoEmpleado();
                            $new->setInicio($this->getCuenta()->getUltimoLote());
                            $new->setFin($fin->modify('+7 day'));
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            $array[] = $new;
                            for ($i = 1; $i < $this->entero($dias); $i++) {
                                $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                                $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                                $new = new SueldoEmpleado();
                                $new->setInicio($ultimo);
                                $new->setFin($fin->modify('+7 day'));
                                $new->setCuenta($this->getCuenta());
                                $new->setDescripcion('Pago de sueldo');
                                $new->setFecha($fin);
                                $new->setMonto($this->getCargo()->getMonto());
                                $array[] = $new;
                            }
                            return $array;
                        }
                    }
                    if ($this->getCargo()->getTipo() == 0) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $mes = $resul->format('%m');
                        if ($mes > 0) {
                            $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $new = new SueldoEmpleado();
                            $new->setInicio($this->getCuenta()->getUltimoLote());
                            $new->setFin($fin->modify('+1 month'));
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            $array[] = $new;
                            for ($i = 1; $i < $mes; $i++) {
                                $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                                $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                                $new = new SueldoEmpleado();
                                $new->setInicio($ultimo);
                                $new->setFin($fin->modify('+1 month'));
                                $new->setCuenta($this->getCuenta());
                                $new->setDescripcion('Pago de sueldo');
                                $new->setFecha($fin);
                                $new->setMonto($this->getCargo()->getMonto());
                                $array[] = $new;
                            }
                            return $array;
                        }
                    }
                }
            }
        }
        //  date_format($hoy, 'd');
        return -1;
    }

    public function pagoPrimerPendiente() {
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($this->primerPago == null) {

            if ((!($this->getCuenta()->existeFechaSueldo($hoy))) && $this->getCuenta()->getUltimoLote() != $hoy) {
                if ($this->getCuenta()->getUltimoLote() != null) {
                    if ($this->getCargo()->getTipo() == 1) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $div = $resul->format('%a') / $this->getCargo()->getPorDia();
                        if ($div >= 1) {
                            $inicio = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $haber = $this->getCuenta()->getSaldo() + $this->getCargo()->getMonto();
                            $this->getCuenta()->setSaldo($haber);
                            if (($div - 1) >= 1) {
                                $this->getCuenta()->setPendiente(1);
                            } else {
                                $this->getCuenta()->setPendiente(0);
                            }
                            $new = new SueldoEmpleado();
                            $this->getCuenta()->addMovimiento($new);
                            $new->setFechaEmision();
                            $new->setInicio($inicio);
                            $new->setFin($fin->modify('+' . $this->getCargo()->getPorDia() . ' day'));
                            $this->getCuenta()->setUltimoLote($fin);
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            return 1;
                        }
                        return 0;
                    }
                    if ($this->getCargo()->getTipo() == 2) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $dias = $resul->format('%a') / 7;
                        if ($dias >= 1) {
                            $inicio = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $haber = $this->getCuenta()->getSaldo() + $this->getCargo()->getMonto();
                            $this->getCuenta()->setSaldo($haber);
                            if (($dias - 1) >= 1) {
                                $this->getCuenta()->setPendiente(1);
                            } else {
                                $this->getCuenta()->setPendiente(0);
                            }
                            $new = new SueldoEmpleado();
                            $this->getCuenta()->addMovimiento($new);
                            $new->setFechaEmision();
                            $new->setInicio($inicio);
                            $new->setFin($fin->modify('+7 day'));
                            $this->getCuenta()->setUltimoLote($fin);
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            return 1;
                        }
                        return 0;
                    }
                    if ($this->getCargo()->getTipo() == 0) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $mes = $resul->format('%m');
                        if ($mes > 0) {
                            $inicio = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $haber = $this->getCuenta()->getSaldo() + $this->getCargo()->getMonto();
                            $this->getCuenta()->setSaldo($haber);
                            if (($mes - 1) >= 1) {
                                $this->getCuenta()->setPendiente(1);
                            } else {
                                $this->getCuenta()->setPendiente(0);
                            }
                            $new = new SueldoEmpleado();
                            $this->getCuenta()->addMovimiento($new);
                            $new->setFechaEmision();
                            $new->setInicio($inicio);
                            $new->setFin($fin->modify('+1 month'));
                            $this->getCuenta()->setUltimoLote($fin);
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            return 1;
                        }
                        return 0;
                    }
                }
            }
        }
        //  date_format($hoy, 'd');
        return -1;
    }

    public function generarListaLiquidacion() {
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        $array = null;
        if ($this->primerPago == null) {

            if ((!($this->getCuenta()->existeFechaSueldo($hoy))) && (!($this->getCuenta()->existeFechaSueldoSuperior($hoy))) && $this->getCuenta()->getUltimoLote() != $hoy) {
                if ($this->getCuenta()->getUltimoLote() != null) {
                    if ($this->getCargo()->getTipo() == 1) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $div = $resul->format('%a') / $this->getCargo()->getPorDia();
                        $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                        if ($div >= 1) {
                            $new = new SueldoEmpleado();
                            $new->setInicio($this->getCuenta()->getUltimoLote());
                            $new->setFin($fin->modify('+' . $this->getCargo()->getPorDia() . ' day'));
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            $array[] = $new;
                            for ($i = 1; $i < $this->entero($div); $i++) {
                                $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                                $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                                $new = new SueldoEmpleado();
                                $new->setInicio($ultimo);
                                $new->setFin($fin->modify('+' . $this->getCargo()->getPorDia() . ' day'));
                                $new->setCuenta($this->getCuenta());
                                $new->setDescripcion('Pago de sueldo');
                                $new->setFecha($fin);
                                $new->setMonto($this->getCargo()->getMonto());
                                $array[] = $new;
                            }
                        }

                        //el resto
                        $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                        $mod = $resul->format('%a') % $this->getCargo()->getPorDia();
                        $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                        $new = new SueldoEmpleado();
                        $new->setFin($fin->modify('+' . $mod . ' day'));
                        if ($fin > $ultimo) {
                            $new->setInicio($ultimo);
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $monto = ($div - $this->entero($div)) * $this->getCargo()->getMonto();
                            $new->setMonto($monto);
                            $array[] = $new;
                        }

                        return $array;
                    }
                    if ($this->getCargo()->getTipo() == 2) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $dias = $resul->format('%a') / 7;
                        $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                        if ($dias >= 1) {
                            //$fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $new = new SueldoEmpleado();
                            $new->setInicio($this->getCuenta()->getUltimoLote());
                            $new->setFin($fin->modify('+7 day'));
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            $array[] = $new;
                            for ($i = 1; $i < $this->entero($dias); $i++) {
                                $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                                $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                                $new = new SueldoEmpleado();
                                $new->setInicio($ultimo);
                                $new->setFin($fin->modify('+7 day'));
                                $new->setCuenta($this->getCuenta());
                                $new->setDescripcion('Pago de sueldo');
                                $new->setFecha($fin);
                                $new->setMonto($this->getCargo()->getMonto());
                                $array[] = $new;
                            }
                        }
                        //el resto
                        $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                        $mod = $resul->format('%a') % 7;
                        $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                        $new = new SueldoEmpleado();
                        $new->setFin($fin->modify('+' . $mod . 'day'));
                        if ($fin > $ultimo) {
                            $new->setInicio($ultimo);
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $monto = ($dias - $this->entero($dias)) * $this->getCargo()->getMonto();
                            $new->setMonto($monto);
                            $array[] = $new;
                        }

                        return $array;
                    }
                    if ($this->getCargo()->getTipo() == 0) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $mes = $resul->format('%m');
                        $fechaDia = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                        $fechaDia->modify('+' . $mes . ' month');
                        $result2 = $fechaDia->diff($hoy);
                        $cantDias = $result2->format('%d');
                        $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                        if ($mes > 0) {
                            $new = new SueldoEmpleado();
                            $new->setInicio($this->getCuenta()->getUltimoLote());
                            $new->setFin($fin->modify('+1 month'));
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            $array[] = $new;
                            for ($i = 1; $i < $mes; $i++) {
                                $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                                $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                                $new = new SueldoEmpleado();
                                $new->setInicio($ultimo);
                                $new->setFin($fin->modify('+1 month'));
                                $new->setCuenta($this->getCuenta());
                                $new->setDescripcion('Pago de sueldo');
                                $new->setFecha($fin);
                                $new->setMonto($this->getCargo()->getMonto());
                                $array[] = $new;
                            }
                        }
                        $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                        $monto = ($cantDias / 30) * $this->getCargo()->getMonto();
                        $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                        $new->setFin($fin->modify('+' . $cantDias . ' day'));
                        if ($fin > $ultimo) {
                            $new = new SueldoEmpleado();
                            $new->setInicio($ultimo);
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($monto);
                            $array[] = $new;
                        }

                        return $array;
                    }
                }
            }
        }
        //  date_format($hoy, 'd');
        return -1;
    }

    public function liquidarListaLiquidacion() {
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        $array = null;
        if ($this->primerPago == null && count($this->getCuenta()->getMovimientos()) > 0) {

            if ((!($this->getCuenta()->existeFechaSueldo($hoy))) && (!($this->getCuenta()->existeFechaSueldoSuperior($hoy))) && $this->getCuenta()->getUltimoLote() != $hoy) {
                if ($this->getCuenta()->getUltimoLote() != null) {
                    if ($this->getCargo()->getTipo() == 1) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $div = $resul->format('%a') / $this->getCargo()->getPorDia();
                        $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                        if ($div >= 1) {
                            for ($i = 0; $i < $this->entero($div); $i++) {
                                $inicio = new \DateTime(date_format($fin, 'Y/m/d'));
                                $haber = $this->getCuenta()->getSaldo() + $this->getCargo()->getMonto();
                                $this->getCuenta()->setSaldo($haber);
                                $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                                $new = new SueldoEmpleado();
                                $this->getCuenta()->addMovimiento($new);
                                $new->setFechaEmision();
                                $new->setInicio($inicio);
                                $new->setFin($fin->modify('+' . $this->getCargo()->getPorDia() . ' day'));
                                $this->getCuenta()->setUltimoLote($fin);
                                $new->setCuenta($this->getCuenta());
                                $new->setDescripcion('Pago de sueldo (liquidación por edición de cargo)');
                                $new->setFecha($fin);
                                $new->setMonto($this->getCargo()->getMonto());
                            }
                        }

                        //el resto
                        $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                        $mod = $resul->format('%a') % $this->getCargo()->getPorDia();
                        $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                        $new = new SueldoEmpleado();
                        $new->setFin($fin->modify('+' . $mod . ' day'));
                        if ($fin > $ultimo) {
                            $new->setFechaEmision();
                            $this->getCuenta()->addMovimiento($new);
                            $new->setInicio($ultimo);
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo (liquidación por edición de cargo)');
                            $new->setFecha($fin);
                            $monto = ($div - $this->entero($div)) * $this->getCargo()->getMonto();
                            $new->setMonto($monto);
                            $haber = $this->getCuenta()->getSaldo() + $monto;
                            $this->getCuenta()->setSaldo($haber);
                            $this->getCuenta()->setPendiente(0);
                        }
                        return 1;
                    }
                    if ($this->getCargo()->getTipo() == 2) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $dias = $resul->format('%a') / 7;
                        $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                        if ($dias >= 1) {
                            for ($i = 0; $i < $this->entero($dias); $i++) {
                                $inicio = new \DateTime(date_format($fin, 'Y/m/d'));
                                $haber = $this->getCuenta()->getSaldo() + $this->getCargo()->getMonto();
                                $this->getCuenta()->setSaldo($haber);
                                $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                                $new = new SueldoEmpleado();
                                $this->getCuenta()->addMovimiento($new);
                                $new->setFechaEmision();
                                $new->setInicio($inicio);
                                $new->setFin($fin->modify('+7 day'));
                                $this->getCuenta()->setUltimoLote($fin);
                                $new->setCuenta($this->getCuenta());
                                $new->setDescripcion('Pago de sueldo (liquidación por edición de cargo)');
                                $new->setFecha($fin);
                                $new->setMonto($this->getCargo()->getMonto());
                            }
                        }
                        //el resto
                        $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                        $mod = $resul->format('%a') % 7;
                        $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                        $new = new SueldoEmpleado();
                        $new->setFin($fin->modify('+' . $mod . ' day'));
                        if ($fin > $ultimo) {
                            $new->setFechaEmision();
                            $this->getCuenta()->addMovimiento($new);
                            $new->setInicio($ultimo);
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo (liquidación por edición de cargo)');
                            $new->setFecha($fin);
                            $monto = ($dias - $this->entero($dias)) * $this->getCargo()->getMonto();
                            $new->setMonto($monto);
                            $haber = $this->getCuenta()->getSaldo() + $monto;
                            $this->getCuenta()->setSaldo($haber);
                            $this->getCuenta()->setPendiente(0);
                        }
                        return 1;
                    }
                    if ($this->getCargo()->getTipo() == 0) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $mes = $resul->format('%m');
                        $fechaDia = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                        $fechaDia->modify('+' . $mes . ' month');
                        $result2 = $fechaDia->diff($hoy);
                        $cantDias = $result2->format('%d');
                        $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                        if ($mes > 0) {
                            for ($i = 0; $i < $mes; $i++) {
                                $inicio = new \DateTime(date_format($fin, 'Y/m/d'));
                                $haber = $this->getCuenta()->getSaldo() + $this->getCargo()->getMonto();
                                $this->getCuenta()->setSaldo($haber);
                                $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                                $new = new SueldoEmpleado();
                                $new->setFechaEmision();
                                $this->getCuenta()->addMovimiento($new);
                                $new->setInicio($inicio);
                                $new->setFin($fin->modify('+1 month'));
                                $this->getCuenta()->setUltimoLote($fin);
                                $new->setCuenta($this->getCuenta());
                                $new->setDescripcion('Pago de sueldo (liquidación por edición de cargo)');
                                $new->setFecha($fin);
                                $new->setMonto($this->getCargo()->getMonto());
                            }
                        }
                        $fin = new \DateTime(date_format($fin, 'Y/m/d'));
                        $monto = ($cantDias / 30) * $this->getCargo()->getMonto();
                        $ultimo = new \DateTime(date_format($fin, 'Y/m/d'));
                        $new = new SueldoEmpleado();
                        $new->setFechaEmision();
                        $new->setFin($fin->modify('+' . $cantDias . ' day'));
                        if ($fin > $ultimo) {
                            $this->getCuenta()->addMovimiento($new);
                            $new->setInicio($ultimo);
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo (liquidación por edición de cargo)');
                            $new->setFecha($fin);
                            $new->setMonto($monto);
                            $haber = $monto + $this->getCuenta()->getSaldo();
                            $this->getCuenta()->setSaldo($haber);
                            $this->getCuenta()->setUltimoLote($fin);
                        }

                        return 1;
                    }
                }
            }
        }
        //  date_format($hoy, 'd');
        return -1;
    }

    public function liquidarPendiente() {
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($this->primerPago == null) {
            if ((!($this->getCuenta()->existeFechaSueldo($hoy))) && $this->getCuenta()->getUltimoLote() != $hoy) {
                if ($this->getCuenta()->getUltimoLote() != null) {
                    if ($this->getCargo()->getTipo() == 1) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $div = $resul->format('%a') / $this->getCargo()->getPorDia();
                        $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                        $haber = $this->getCuenta()->getSaldo() + ($this->getCargo()->getMonto() * $div);
                        $this->getCuenta()->setSaldo($haber);
                        //ver el tema del lote y de las fechas
                        $this->getCuenta()->setUltimoLote($fin);
                        $this->getCuenta()->setPendiente(0);
                        $new = new SueldoEmpleado();
                        $this->getCuenta()->addMovimiento($new);
                        $new->setFechaEmision();
                        $new->setInicio($this->getCuenta()->getUltimoLote());
                        $new->setFin($fin->modify('+' . $this->getCargo()->getPorDia() . ' day'));
                        $new->setCuenta($this->getCuenta());
                        $new->setDescripcion('Pago de sueldo total');
                        $new->setFecha($fin);
                        $new->setMonto($this->getCargo()->getMonto());
                        return 1;

                        return 0;
                    }
                    if ($this->getCargo()->getTipo() == 2) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $dias = $resul->format('%a') / 7;
                        if ($dias >= 1) {
                            $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $haber = $this->getCuenta()->getSaldo() + $this->getCargo()->getMonto();
                            $this->getCuenta()->setSaldo($haber);
                            $this->getCuenta()->setUltimoLote($fin);
                            if (($dias - 1) >= 1) {
                                $this->getCuenta()->setPendiente(1);
                            } else {
                                $this->getCuenta()->setPendiente(0);
                            }
                            $new = new SueldoEmpleado();
                            $this->getCuenta()->addMovimiento($new);
                            $new->setFechaEmision();
                            $new->setInicio($this->getCuenta()->getUltimoLote());
                            $new->setFin($fin->modify('+7 month'));
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            return 1;
                        }
                        return 0;
                    }
                    if ($this->getCargo()->getTipo() == 0) {
                        $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                        $mes = $resul->format('%m');
                        if ($mes > 0) {
                            $fin = new \DateTime(date_format($this->getCuenta()->getUltimoLote(), 'Y/m/d'));
                            $haber = $this->getCuenta()->getSaldo() + $this->getCargo()->getMonto();
                            $this->getCuenta()->setSaldo($haber);
                            $this->getCuenta()->setUltimoLote($fin);
                            if (($mes - 1) >= 1) {
                                $this->getCuenta()->setPendiente(1);
                            } else {
                                $this->getCuenta()->setPendiente(0);
                            }
                            $new = new SueldoEmpleado();
                            $this->getCuenta()->addMovimiento($new);
                            $new->setFechaEmision();
                            $new->setInicio($this->getCuenta()->getUltimoLote());
                            $new->setFin($fin->modify('+1 month'));
                            $new->setCuenta($this->getCuenta());
                            $new->setDescripcion('Pago de sueldo');
                            $new->setFecha($fin);
                            $new->setMonto($this->getCargo()->getMonto());
                            return 1;
                        }
                        return 0;
                    }
                }
            }
        }
        //  date_format($hoy, 'd');
        return -1;
    }

    public function cantiadDePendientes() {
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($this->getCuenta()->getUltimoLote() != null) {
            if ($this->getCargo()->getTipo() == 1) {
                $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                $div = $resul->format('%a') / $this->getCargo()->getPorDia();
                return $this->entero($div);
            }
            if ($this->getCargo()->getTipo() == 2) {
                $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                $dias = $resul->format('%a') / 7;
                return $this->entero($dias);
            }
            if ($this->getCargo()->getTipo() == 0) {
                $resul = $hoy->diff($this->getCuenta()->getUltimoLote());
                $mes = $resul->format('%m');

                return $mes;
            }
        } else {
            return -1;
        }
    }

    public function disponibilidadContado(MovEmpleado $cont) {
        if (($cont->getCuenta()->getSaldo() - $cont->getMonto()) >= 0) {
            return 0;
        } elseif ((($cont->getCuenta()->getSaldo() + $this->getCargo()->getNegativo()) - $cont->getMonto()) >= 0) {
            return 1;
        }
        return -1;
    }

    private function entero($num) {
        $var = explode('.', $num);
        return $var[0];
    }

    /**
     * Set primerPago
     *
     * @param \DateTime $primerPago
     * @return PersonaEmpleado
     */
    public function setPrimerPago($primerPago) {
        $this->primerPago = $primerPago;

        return $this;
    }

    /**
     * Get primerPago
     *
     * @return \DateTime 
     */
    public function getPrimerPago() {
        return $this->primerPago;
    }


    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     * @return PersonaEmpleado
     */
    public function setFechaIngreso($fechaIngreso) {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime 
     */
    public function getFechaIngreso() {
        return $this->fechaIngreso;
    }

    /**
     * Set foto
     *
     * @param \SisGG\FinalBundle\Entity\Image $foto
     * @return PersonaEmpleado
     */
    public function setFoto(\SisGG\FinalBundle\Entity\Image $foto = null) {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return \SisGG\FinalBundle\Entity\Image 
     */
    public function getFoto() {
        return $this->foto;
    }

    function getCargo(){
        
        foreach ($this->cargos as $value) {
            if ($value->getActivo()){
                return $value;
            }
        }
        return null;
    }


    /**
     * Add cargos
     *
     * @param \SisGG\FinalBundle\Entity\CargoEmpleado $cargos
     * @return PersonaEmpleado
     */
    public function addCargo(\SisGG\FinalBundle\Entity\CargoEmpleado $cargos)
    {
        $this->cargos[] = $cargos;
    
        return $this;
    }

    /**
     * Remove cargos
     *
     * @param \SisGG\FinalBundle\Entity\CargoEmpleado $cargos
     */
    public function removeCargo(\SisGG\FinalBundle\Entity\CargoEmpleado $cargos)
    {
        $this->cargos->removeElement($cargos);
    }

    /**
     * Get cargos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCargos()
    {
        return $this->cargos;
    }
    
    public function getCargoAct() {
        return $this->cargoAct;
    }

    public function setCargoAct($cargoAct) {
        $this->cargoAct = $cargoAct;
    }
    
    public function getEdad() {
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        $resul = $hoy->diff($this->fechaNac );
        return $resul->format('%y');
    }

    public function enLetras($numero) {
        $new=new EnLetras();
        return strtoupper($new->ValorEnLetras($numero, 'Pesos'));
    }
    
    public function getTipoSexo() {
        if ($this->sexo ){
            return "M";
        }else{
            return "F";
        }
    }
    
    public function getestadoSueldo() {
        if ($this->getCuenta()->getPendiente() ){
            return "Pendiente";
        }else{
            return "Pagado";
        }
    }
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var boolean
     */
    protected $isActive;


    /**
     * Set dni
     *
     * @param string $dni
     * @return PersonaEmpleado
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    
        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }


    /**
     * Set username
     *
     * @param string $username
     * @return PersonaEmpleado
     */
    public function setUsername($username)
    {
        $this->username =str_replace(' ', '', $username);
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return PersonaEmpleado
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return PersonaEmpleado
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return PersonaEmpleado
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set direccion
     *
     * @param \SisGG\FinalBundle\Entity\Direccion $direccion
     * @return PersonaEmpleado
     */
    public function setDireccion(\SisGG\FinalBundle\Entity\Direccion $direccion = null)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return \SisGG\FinalBundle\Entity\Direccion 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Add telefonos
     *
     * @param \SisGG\FinalBundle\Entity\Telefono $telefonos
     * @return PersonaEmpleado
     */
    public function addTelefono(\SisGG\FinalBundle\Entity\Telefono $telefonos)
    {
        $this->telefonos[] = $telefonos;
    
        return $this;
    }

    /**
     * Remove telefonos
     *
     * @param \SisGG\FinalBundle\Entity\Telefono $telefonos
     */
    public function removeTelefono(\SisGG\FinalBundle\Entity\Telefono $telefonos)
    {
        $this->telefonos->removeElement($telefonos);
    }

    /**
     * Get telefonos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTelefonos()
    {
        return $this->telefonos;
    }
    /**
     * @var string
     */
    protected $apellido;

    /**
     * @var string
     */
    protected $nombre;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var \SisGG\FinalBundle\Entity\Rol
     */
    protected $role;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $mensajes;


    /**
     * Set apellido
     *
     * @param string $apellido
     * @return PersonaEmpleado
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    
        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return PersonaEmpleado
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
     * Set email
     *
     * @param string $email
     * @return PersonaEmpleado
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set role
     *
     * @param \SisGG\FinalBundle\Entity\Rol $role
     * @return PersonaEmpleado
     */
    public function setRole(\SisGG\FinalBundle\Entity\Rol $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return \SisGG\FinalBundle\Entity\Rol 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Add mensajes
     *
     * @param \SisGG\FinalBundle\Entity\ChatMensaje $mensajes
     * @return PersonaEmpleado
     */
    public function addMensaje(\SisGG\FinalBundle\Entity\ChatMensaje $mensajes)
    {
        $this->mensajes[] = $mensajes;
    
        return $this;
    }

    /**
     * Remove mensajes
     *
     * @param \SisGG\FinalBundle\Entity\ChatMensaje $mensajes
     */
    public function removeMensaje(\SisGG\FinalBundle\Entity\ChatMensaje $mensajes)
    {
        $this->mensajes->removeElement($mensajes);
    }

    /**
     * Get mensajes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajes()
    {
        return $this->mensajes;
    }
    public function isEmpleado() {
        return true;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return PersonaEmpleado
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
  

   
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $backups;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $agendas;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $etiquetas;


    /**
     * Add backups
     *
     * @param \SisGG\FinalBundle\Entity\Backup $backups
     * @return PersonaEmpleado
     */
    public function addBackup(\SisGG\FinalBundle\Entity\Backup $backups)
    {
        $this->backups[] = $backups;
    
        return $this;
    }

    /**
     * Remove backups
     *
     * @param \SisGG\FinalBundle\Entity\Backup $backups
     */
    public function removeBackup(\SisGG\FinalBundle\Entity\Backup $backups)
    {
        $this->backups->removeElement($backups);
    }

    /**
     * Get backups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBackups()
    {
        return $this->backups;
    }

    /**
     * Add agendas
     *
     * @param \SisGG\FinalBundle\Entity\Agenda $agendas
     * @return PersonaEmpleado
     */
    public function addAgenda(\SisGG\FinalBundle\Entity\Agenda $agendas)
    {
        $this->agendas[] = $agendas;
    
        return $this;
    }

    /**
     * Remove agendas
     *
     * @param \SisGG\FinalBundle\Entity\Agenda $agendas
     */
    public function removeAgenda(\SisGG\FinalBundle\Entity\Agenda $agendas)
    {
        $this->agendas->removeElement($agendas);
    }

    /**
     * Get agendas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAgendas()
    {
        return $this->agendas;
    }

    /**
     * Add etiquetas
     *
     * @param \SisGG\FinalBundle\Entity\EtiquetaAgenda $etiquetas
     * @return PersonaEmpleado
     */
    public function addEtiqueta(\SisGG\FinalBundle\Entity\EtiquetaAgenda $etiquetas)
    {
        $this->etiquetas[] = $etiquetas;
    
        return $this;
    }

    /**
     * Remove etiquetas
     *
     * @param \SisGG\FinalBundle\Entity\EtiquetaAgenda $etiquetas
     */
    public function removeEtiqueta(\SisGG\FinalBundle\Entity\EtiquetaAgenda $etiquetas)
    {
        $this->etiquetas->removeElement($etiquetas);
    }

    /**
     * Get etiquetas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEtiquetas()
    {
        return $this->etiquetas;
    }

   

}