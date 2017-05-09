<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Persona;
use Symfony\Component\Security\Core\User\UserInterface;
use \Serializable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @UniqueEntity(fields="username", message="Ya existe una persona con este nombre de usuario.")
 * @UniqueEntity(fields="email", message="Ya existe una persona con este e-mail.")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"persona_empleado" = "PersonaEmpleado", "usuario"="Usuario"})
 * @Gedmo\Loggable
 */
class Usuario implements UserInterface, AdvancedUserInterface, Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese un apellido")
     * @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="El apellido no puede contener números") 
     * @Gedmo\Versioned
     */
    protected $apellido;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese un nombre")
     *  @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="El nombre no puede contener números") 
     * @Gedmo\Versioned
    */
    protected $nombre;
   
    
    /**
     * @Assert\Email(
     *     message = "El email '{{ value }}' no es un email valido."
     * )
     * @Assert\NotBlank(message="Ingrese un email")
     * @ORM\Column(type="string",nullable=false)
     * @Gedmo\Versioned
     */
    protected $email;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese un nombre de usuario")
     * @Gedmo\Versioned
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=32)
     * @Gedmo\Versioned
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank
     * @Gedmo\Versioned
     */
    protected $password;

    

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @Gedmo\Versioned
     */
    protected $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="Rol", inversedBy="users")
     * @Gedmo\Versioned
     *
     */
    protected $role;

    /**
     * @ORM\OneToMany(targetEntity="ChatMensaje",mappedBy="usuario")
     */
    protected $mensajes;
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="usuarios")
     */
    protected $empresa;
    /**
     * @ORM\OneToMany(targetEntity="Backup", mappedBy="empresa")
     */
    protected $backups;
    /**
     * @ORM\OneToMany(targetEntity="Agenda", mappedBy="usuario")
     */
    protected $agendas;
     /**
     * @ORM\OneToMany(targetEntity="EtiquetaAgenda", mappedBy="usuario")
     */
    protected $etiquetas;
     /**
     * @ORM\OneToMany(targetEntity="Redireccion",mappedBy="usuarioA")
     */
    private $redirecciones;
    
    /**
     * @ORM\OneToMany(targetEntity="Pedido",mappedBy="usuario")
     */
    private $pedidos;
    
    /**
     * @ORM\OneToMany(targetEntity="Venta",mappedBy="usuario")
     */
    private $ventas;
    public function __construct() {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        
    }

    /**
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles() {
        return array('ROLE_USER');
    }
    
    public function __toString() {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        
    }

    /**
     * @inheritDoc
     */
    public function equals(UserInterface $user) {
        return $this->username === $user->getUsername();
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
     * Set username
     *
     * @param string $username
     * @return Usuario
     */
    public function setUsername($username) {
        $this->username =str_replace(' ', '', $username);

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return Usuario
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * Set role
     *
     * @param SisGG\FinalBundle\Entity\Rol $role
     * @return Usuario
     */
    public function setRole(\SisGG\FinalBundle\Entity\Rol $role = null) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return SisGG\FinalBundle\Entity\Rol 
     */
    public function getRole() {
        return $this->role;
    }

    public function obtenerEnviados(Usuario $destino) {
        $array = null;
        if ($this->mensajes == null || count($this->mensajes) <= 0)
            return null;
        foreach ($this->mensajes as $value) {
            if (( $value->getDestino() == $destino)&&($value->getEliminar()!=$this->getId())) {
                $array[] = $value;
            }
        }
        return $array;
    }

    public function obtenerRecibidos(Usuario $destino) {
        $array = null;
        if ($destino->mensajes == null || count($destino->mensajes) <= 0)
            return null;
        foreach ($destino->mensajes as $value) {
            if (( $value->getDestino() == $this&&($value->getEliminar()!=$this->getId()))) {
                $array[] = $value;
            }
        }
        return $array;
    }

    public function obtenerInteractuados(Usuario $destino, $ordenado = false) {
        $array = array_merge((Array) $this->obtenerEnviados($destino), (Array) $this->obtenerRecibidos($destino));
        if ($ordenado)
            asort($array);
        return $array;
    }
    
     public function cantidadMensajes( $destino) {
        $empresa= $this->getEmpresa();
        $usuario =  $empresa->obtenerUsuario($destino);
        if ($usuario==null){
            return 0;
        }
        
        return count($this->obtenerInteractuados($usuario));
    }
    
    public function obtenerMensajesNoLeidos(Usuario $destino) {
        $array=  $this->obtenerRecibidos($destino);
        if ($array == null || count($array) <= 0)
            return null;
        $retorno=null;
        foreach ($array as $value) {
            if ($value->getLeido()==1){
                $retorno[]=$value;
            }
        }
        return $retorno;
    }
    public function obtenerMensajesNuevos(Usuario $destino) {
        $array=  $this->obtenerRecibidos($destino);
        if ($array == null || count($array) <= 0)
            return null;
        $retorno=null;
        foreach ($array as $value) {
            if ($value->getEnvio()==1){
                $retorno[]=$value;
            }
        }
        return $retorno;
    }
    public function hayNoLeido($destino) {
        $empresa= $this->getEmpresa();
        $usuario =  $empresa->obtenerUsuario($destino);
        $array=  $this->obtenerRecibidos($usuario);
        if ($array == null || count($array) <= 0)
            return null;
        foreach ($array as $value) {
            if ($value->getLeido()){
                return $value->getId();
            }
        }
        return false;
    }
    
    public function hayNuevo(Usuario $destino) {
        $array=  $this->obtenerRecibidos($destino);
        if ($array == null || count($array) <= 0)
            return null;
        foreach ($array as $value) {
            if ($value->getEnvio()==1){
                return true;
            }
        }
        return false;
    }
    
    public function cantidadNoLeidos( $destino) {
        $empresa= $this->getEmpresa();
        $usuario =  $empresa->obtenerUsuario($destino);
        if ($usuario==null){
            return 0;
        }
        return count($this->obtenerMensajesNoLeidos($usuario));
    }
     public function cantidadNuevos( $destino) {
        $empresa= $this->getEmpresa();
        $usuario =  $empresa->obtenerUsuario($destino);
        if ($usuario==null){
            return 0;
        }
        return count($this->obtenerMensajesNuevos($usuario));
    }

    /**
     * Add mensajes
     *
     * @param \SisGG\FinalBundle\Entity\ChatMensaje $mensajes
     * @return Usuario
     */
    public function addMensaje(\SisGG\FinalBundle\Entity\ChatMensaje $mensajes) {
        $this->mensajes[] = $mensajes;

        return $this;
    }

    /**
     * Remove mensajes
     *
     * @param \SisGG\FinalBundle\Entity\ChatMensaje $mensajes
     */
    public function removeMensaje(\SisGG\FinalBundle\Entity\ChatMensaje $mensajes) {
        $this->mensajes->removeElement($mensajes);
    }

    /**
     * Get mensajes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajes() {
        return $this->mensajes;
    }


    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Usuario
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return \SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return Usuario
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
     * @return Usuario
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
    
    public function isEmpleado() {
        return false;
    }

    /**
     * Add backups
     *
     * @param \SisGG\FinalBundle\Entity\Backup $backups
     * @return Usuario
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

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->isActive;
    }

    /**
     * Add agendas
     *
     * @param \SisGG\FinalBundle\Entity\Agenda $agendas
     * @return Usuario
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
     * @return Usuario
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
    
    

    /**
     * Add redirecciones
     *
     * @param \SisGG\FinalBundle\Entity\Redireccion $redirecciones
     * @return Usuario
     */
    public function addRedireccione(\SisGG\FinalBundle\Entity\Redireccion $redirecciones)
    {
        $this->redirecciones[] = $redirecciones;
    
        return $this;
    }

    /**
     * Remove redirecciones
     *
     * @param \SisGG\FinalBundle\Entity\Redireccion $redirecciones
     */
    public function removeRedireccione(\SisGG\FinalBundle\Entity\Redireccion $redirecciones)
    {
        $this->redirecciones->removeElement($redirecciones);
    }

    /**
     * Get redirecciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRedirecciones()
    {
        return $this->redirecciones;
    }

    /**
     * Add pedidos
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $pedidos
     * @return Usuario
     */
    public function addPedido(\SisGG\FinalBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos[] = $pedidos;
    
        return $this;
    }

    /**
     * Remove pedidos
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $pedidos
     */
    public function removePedido(\SisGG\FinalBundle\Entity\Pedido $pedidos)
    {
        $this->pedidos->removeElement($pedidos);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }

    /**
     * Add ventas
     *
     * @param \SisGG\FinalBundle\Entity\Venta $ventas
     * @return Usuario
     */
    public function addVenta(\SisGG\FinalBundle\Entity\Venta $ventas)
    {
        $this->ventas[] = $ventas;
    
        return $this;
    }

    /**
     * Remove ventas
     *
     * @param \SisGG\FinalBundle\Entity\Venta $ventas
     */
    public function removeVenta(\SisGG\FinalBundle\Entity\Venta $ventas)
    {
        $this->ventas->removeElement($ventas);
    }

    /**
     * Get ventas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVentas()
    {
        return $this->ventas;
    }
     public function obtenerAgendaPendientes() {
        if ($this->getAgendas()==null || count($this->getAgendas())<=0){
            return null;
        }
        $array=null;
        $hoy = new \DateTime('now');
        foreach ($this->getAgendas() as $value) {
            /*@var $value Agenda*/
            $fecha = new \DateTime(date_format($value->getInicioFec(), 'Y/m/d').' '.date_format($value->getInicioHora(), 'H:i:s'));
            if ($hoy>$fecha && $value->getVisto()==false){
                $array[]=$value;
            }
        }
        return $array;
    }
}