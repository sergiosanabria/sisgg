<?php

namespace SisGG\FinalBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\CocinaRepository")
 */
class Cocina implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroTandas;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $tamanoMaximoEspera;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $tamanoMaximoTandas;

    /**
     * @ORM\OneToMany(targetEntity="MarcaTemporal",mappedBy="cocina",cascade="persist")  
     */
    private $marcasTemporales;

    /**
     * @ORM\OneToMany(targetEntity="Tanda",mappedBy="cocina",cascade="persist") 
     */
    private $tandas;

    /**
     * @ORM\OneToMany(targetEntity="Cocina",mappedBy="cocina") 
     */
    private $pedidos;

    public function __toString() {
        return $this->nombre;
    }

    /**
     * @return string
     */
    public function serialize() {
        return serialize($this->id);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized) {
        $this->id = unserialize($serialized);
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->tandas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marcasTemporales = new \Doctrine\Common\Collections\ArrayCollection();
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

      /**
     * Set numeroTandas
     *
     * @param integer $numeroTandas
     * @return Cocina
     */
    public function setNumeroTandas($numeroTandas) {
        $this->numeroTandas = $numeroTandas;

        return $this;
    }

    /* Set nombre
     *
     * @param string $nombre
     * @return Cocina
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
     * @param integer $numero
     */
    public function cambiarNumeroTandas($numero) {
        $resultado = $numero - $this->tandas->count();
        if ($resultado > 0) {
            for ($index = 0; $index < $resultado; $index++) {
                $tanda = new Tanda();
                $tanda->setCocina($this);
                $this->getTandas()->add($tanda);
            }
        } else if ($resultado < 0) {
            $resultado = $resultado * -1;
            for ($index = 0; $index < $resultado; $index++) {
                $tanda = $this->getTandas()->last();
                foreach ($tanda->getComandas() as $comanda) {
                    $tanda->desvincularComanda($comanda);
                }
                $tanda->setCocina(null);
                $this->getTandas()->removeElement($tanda);
            }
        }
    }

    public function getNumero() {
        return $this->tandas->count();
    }

    public function setNumero($numero) {
        
    }

    /**
     * Add tandas
     *
     * @param \SisGG\FinalBundle\Entity\Tanda $tandas
     * @return Cocina
     */
    public function addTanda(\SisGG\FinalBundle\Entity\Tanda $tandas) {
        $this->tandas[] = $tandas;

        return $this;
    }

    /**
     * Remove tandas
     *
     * @param \SisGG\FinalBundle\Entity\Tanda $tandas
     */
    public function removeTanda(\SisGG\FinalBundle\Entity\Tanda $tandas) {
        $this->tandas->removeElement($tandas);
    }

    /**
     * Get tandas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTandas() {
        return $this->tandas;
    }

    /**
     * Add pedidos
     *
     * @param \SisGG\FinalBundle\Entity\Cocina $pedidos
     * @return Cocina
     */
    public function addPedido(\SisGG\FinalBundle\Entity\Cocina $pedidos) {
        $this->pedidos[] = $pedidos;

        return $this;
    }

    /**
     * Remove pedidos
     *
     * @param \SisGG\FinalBundle\Entity\Cocina $pedidos
     */
    public function removePedido(\SisGG\FinalBundle\Entity\Cocina $pedidos) {
        $this->pedidos->removeElement($pedidos);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPedidos() {
        return $this->pedidos;
    }

    /**
     * Add marcasTemporales
     *
     * @param \SisGG\FinalBundle\Entity\MarcaTemporal $marcasTemporales
     * @return Cocina
     */
    public function addMarcasTemporale(\SisGG\FinalBundle\Entity\MarcaTemporal $marcasTemporales) {
        $this->marcasTemporales[] = $marcasTemporales;

        return $this;
    }

    /**
     * Remove marcasTemporales
     *
     * @param \SisGG\FinalBundle\Entity\MarcaTemporal $marcasTemporales
     */
    public function removeMarcasTemporale(\SisGG\FinalBundle\Entity\MarcaTemporal $marcasTemporales) {
        $this->marcasTemporales->removeElement($marcasTemporales);
    }

    /**
     * Get marcasTemporales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarcasTemporales() {
        return $this->marcasTemporales;
    }

    /**
     * Get numeroTandas
     *
     * @return integer 
     */
    public function getNumeroTandas() {
        return $this->numeroTandas;
    }

    


    /**
     * Set tamanoMaximoEspera
     *
     * @param integer $tamanoMaximoEspera
     * @return Cocina
     */
    public function setTamanoMaximoEspera($tamanoMaximoEspera)
    {
        $this->tamanoMaximoEspera = $tamanoMaximoEspera;
    
        return $this;
    }

    /**
     * Get tamanoMaximoEspera
     *
     * @return integer 
     */
    public function getTamanoMaximoEspera()
    {
        return $this->tamanoMaximoEspera;
    }

    /**
     * Set tamanoMaximoTandas
     *
     * @param integer $tamanoMaximoTandas
     * @return Cocina
     */
    public function setTamanoMaximoTandas($tamanoMaximoTandas)
    {
        $this->tamanoMaximoTandas = $tamanoMaximoTandas;
    
        return $this;
    }

    /**
     * Get tamanoMaximoTandas
     *
     * @return integer 
     */
    public function getTamanoMaximoTandas()
    {
        return $this->tamanoMaximoTandas;
    }
}