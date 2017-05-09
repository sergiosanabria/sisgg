<?php

namespace SisGG\FinalBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\Telefono;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\ProveedorRepository")
 * @ORM\Table(name="proveedor")
 * @UniqueEntity(fields="razonSocial", message="Ya existe una empresa con esta razón social.")
 * @UniqueEntity(fields="cuit", message="Ya existe una empresa con este CUIT.")
 * @Gedmo\Loggable
 */
class Proveedor {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Ingrese la razón social de la empresa.")
     * @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="La razón social no puede contener números.") 
     * @Gedmo\Versioned
     */
    private $razonSocial;

    /**
     * @ORM\OneToOne(targetEntity="Direccion", cascade={"persist"})
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity="Telefono",mappedBy="proveedor",cascade="persist")
     */
    protected $telefonos;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string",nullable=true)
     */
    protected $cuit;

    /**
     * @Assert\Email(
     *     message = "El email '{{ value }}' no es un email valido."
     * )
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Mercaderia",mappedBy="proveedor")
     */
    private $mercaderias;

    /**
     * @ORM\OneToMany(targetEntity="MateriaPrima",mappedBy="proveedor")
     */
    private $materiasprimas;
     /**
     * @ORM\OneToMany(targetEntity="Mantenimiento", mappedBy="proveedor")
     */
    private $mantenimientos;
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="proveedores")
     */
    private $empresa;
                 
    /**
     * 
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $activo;
    /**
     * @ORM\ManyToOne(targetEntity="CondicionIva",inversedBy="clientes")
     */
    private $condicionIva;

    public function serialize() {
        return serialize($this->id);
    }

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
     * Set razonSocial
     *
     * @param string $razonSocial
     * @return Proveedor
     */
    public function setRazonSocial($razonSocial) {
        $this->razonSocial = $razonSocial;

        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return string 
     */
    public function getRazonSocial() {
        return $this->razonSocial;
    }

    /**
     * Set cuit
     *
     * @param SisGG\FinalBundle\Entity\Cuit $cuit
     * @return Proveedor
     */
    public function setCuit($cuit) {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return SisGG\FinalBundle\Entity\Cuit 
     */
    public function getCuit() {
        return $this->cuit;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Proveedor
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set direccion
     *
     * @param SisGG\FinalBundle\Entity\Direccion $direccion
     * @return Proveedor
     */
    public function setDireccion(\SisGG\FinalBundle\Entity\Direccion $direccion = null) {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return SisGG\FinalBundle\Entity\Direccion 
     */
    public function getDireccion() {
        return $this->direccion;
    }

    /**
     * Set empresa
     *
     * @param SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Proveedor
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null) {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa() {
        return $this->empresa;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Proveedor
     */
    public function setActivo($activo) {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo() {
        return $this->activo;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->mercaderias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        $name=$this->getRazonSocial();
        if ($this->getCondicionIva()->getAbreviatura()!=null){
            $name.=" (".$this->getCondicionIva()->getAbreviatura().")";
        }
        
        return $name;
    }

    /**
     * Add mercaderias
     *
     * @param SisGG\FinalBundle\Entity\Mercaderia $mercaderias
     * @return Proveedor
     */
    public function addMercaderia(\SisGG\FinalBundle\Entity\Mercaderia $mercaderias) {
        $this->mercaderias[] = $mercaderias;

        return $this;
    }

    /**
     * Remove mercaderias
     *
     * @param SisGG\FinalBundle\Entity\Mercaderia $mercaderias
     */
    public function removeMercaderia(\SisGG\FinalBundle\Entity\Mercaderia $mercaderias) {
        $this->mercaderias->removeElement($mercaderias);
    }

    /**
     * Get mercaderias
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMercaderias() {
        return $this->mercaderias;
    }

    /**
     * Add materiasprimas
     *
     * @param SisGG\FinalBundle\Entity\MateriaPrima $materiasprimas
     * @return Proveedor
     */
    public function addMateriasprima(\SisGG\FinalBundle\Entity\MateriaPrima $materiasprimas) {
        $this->materiasprimas[] = $materiasprimas;

        return $this;
    }

    /**
     * Remove materiasprimas
     *
     * @param SisGG\FinalBundle\Entity\MateriaPrima $materiasprimas
     */
    public function removeMateriasprima(\SisGG\FinalBundle\Entity\MateriaPrima $materiasprimas) {
        $this->materiasprimas->removeElement($materiasprimas);
    }

    /**
     * Get materiasprimas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMateriasprimas() {
        return $this->materiasprimas;
    }

    /**
     * Add telefonos
     *
     * @param SisGG\FinalBundle\Entity\Telefono $telefonos
     * @return Proveedor
     */
    public function addTelefono(\SisGG\FinalBundle\Entity\Telefono $telefonos) {
        $this->telefonos[] = $telefonos;

        return $this;
    }

    /**
     * Remove telefonos
     *
     * @param SisGG\FinalBundle\Entity\Telefono $telefonos
     */
    public function removeTelefono(\SisGG\FinalBundle\Entity\Telefono $telefonos) {
        $this->telefonos->removeElement($telefonos);
    }

    /**
     * Get telefonos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTelefonos() {
        return $this->telefonos;
    }

    public function existe($proveedores) {
        foreach ($proveedores as $value) {
            If ($this->getRazonSocial() == $value->getRazonSocial()) {
                if (($this->getId() != $value->getId())) {
                    return "El proveedor con la Razón social " . $this->getRazonSocial() . " ya se encuentra registrado.";
                }
            }
            if ($this->getCuit() != '') {
                If ($this->getCuit() == $value->getCuit()) {
                    if (($this->getId() != $value->getId())) {
                        return "El proveedor con el CUIT " . $this->getCuit() . " ya se encuentra registrado.";
                    }
                }
            }
        }
        return null;
    }


    /**
     * Add mantenimientos
     *
     * @param SisGG\FinalBundle\Entity\Mantenimiento $mantenimientos
     * @return Proveedor
     */
    public function addMantenimiento(\SisGG\FinalBundle\Entity\Mantenimiento $mantenimientos)
    {
        $this->mantenimientos[] = $mantenimientos;
    
        return $this;
    }

    /**
     * Remove mantenimientos
     *
     * @param SisGG\FinalBundle\Entity\Mantenimiento $mantenimientos
     */
    public function removeMantenimiento(\SisGG\FinalBundle\Entity\Mantenimiento $mantenimientos)
    {
        $this->mantenimientos->removeElement($mantenimientos);
    }

    /**
     * Get mantenimientos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMantenimientos()
    {
        return $this->mantenimientos;
    }

    
    public function debeFacturas(){
        $compras=$this->getEmpresa()->getCompras();
        foreach ($compras as $value) {
            if ($value->getProveedor()==$this&&$value->getEstado()==false){
                return true;
            }
        }
        return false;
    }
    
    public function saldos() {
        $compras=$this->getEmpresa()->getCompras();
        $total=0.00;
        $pagos=0.00;
        foreach ($compras as $value) {
            if ($value->getProveedor()==$this){
                $total+=$value->getTotal();
                $pagos+=$value->totalPagos();
            }
            
        }
        
        return array('total'=>$total, 'pagos'=>$pagos, 'diff'=>$total-$pagos);
    }
    
    public function tipoIva() {
        if ($this->responsable){
            return 'Resp.Insc.';
        }
        return 'Monotributista';
    }
    public function esta($id, $lista) {
        if ($lista==null){
            return true;
        }
        foreach ($lista as $value) {
            if ($value->getId()==$id){
                return true;
            }
        }
        return false;
    }

    /**
     * Set condicionIva
     *
     * @param \SisGG\FinalBundle\Entity\CondicionIva $condicionIva
     * @return Proveedor
     */
    public function setCondicionIva(\SisGG\FinalBundle\Entity\CondicionIva $condicionIva = null)
    {
        $this->condicionIva = $condicionIva;
    
        return $this;
    }

    /**
     * Get condicionIva
     *
     * @return \SisGG\FinalBundle\Entity\CondicionIva 
     */
    public function getCondicionIva()
    {
        return $this->condicionIva;
    }
}