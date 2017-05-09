<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Valor implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $valor;
    /**
     * @ORM\ManyToOne(targetEntity="Campo",inversedBy="valores",cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $campo;
    /**
     * @ORM\ManyToOne(targetEntity="Cobro",inversedBy="valor",cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $cobro;
    /**
     * @ORM\ManyToOne(targetEntity="Pago",inversedBy="valores",cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $pago;

    /**
     * @return string
     */
    public function serialize()
    {
      return serialize($this->id);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
      $this->id = unserialize($data);
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
     * Set valor
     *
     * @param string $valor
     * @return Valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    
        return $this;
    }

    /**
     * Get valor
     *
     * @return string 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set campo
     *
     * @param \SisGG\FinalBundle\Entity\Campo $campo
     * @return Valor
     */
    public function setCampo(\SisGG\FinalBundle\Entity\Campo $campo = null)
    {
        $this->campo = $campo;
    
        return $this;
    }

    /**
     * Get campo
     *
     * @return \SisGG\FinalBundle\Entity\Campo 
     */
    public function getCampo()
    {
        return $this->campo;
    }

    /**
     * Set cobro
     *
     * @param \SisGG\FinalBundle\Entity\Cobro $cobro
     * @return Valor
     */
    public function setCobro(\SisGG\FinalBundle\Entity\Cobro $cobro = null)
    {
        $this->cobro = $cobro;
    
        return $this;
    }

    /**
     * Get cobro
     *
     * @return \SisGG\FinalBundle\Entity\Cobro 
     */
    public function getCobro()
    {
        return $this->cobro;
    }

    /**
     * Set pago
     *
     * @param \SisGG\FinalBundle\Entity\Pago $pago
     * @return Valor
     */
    public function setPago(\SisGG\FinalBundle\Entity\Pago $pago = null)
    {
        $this->pago = $pago;
    
        return $this;
    }

    /**
     * Get pago
     *
     * @return \SisGG\FinalBundle\Entity\Pago 
     */
    public function getPago()
    {
        return $this->pago;
    }
}