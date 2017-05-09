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
class MovimientoCuentaCorrienteHaber extends MovimientoCuentaCorriente implements \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function isHaber(){
        return true;
    }
    
    /**
     * @Assert\Valid
     * @ORM\OneToOne(targetEntity="Recibo", inversedBy="movimientoCuentaCorriente")
     * @Gedmo\Versioned
     */
    private $unRecibo;
      
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
     * Set cuenta
     *
     * @param \SisGG\FinalBundle\Entity\CuentaCorriente $cuenta
     * @return MovimientoCuentaCorriente
     */
    public function setCuenta(\SisGG\FinalBundle\Entity\CuentaCorriente $cuenta = null) {
        parent::setCuenta($cuenta);
        $cuenta->setSaldo($cuenta->getSaldo() + $this->getImporte());
        return $this;
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
     * Set unRecibo
     *
     * @param \SisGG\FinalBundle\Entity\Recibo $unRecibo
     * @return MovimientoCuentaCorrienteHaber
     */
    public function setUnRecibo(\SisGG\FinalBundle\Entity\Recibo $unRecibo = null)
    {
        $this->unRecibo = $unRecibo;
        $this->setImporte($unRecibo->getTotal());
        return $this;
    }

    /**
     * Get unRecibo
     *
     * @return \SisGG\FinalBundle\Entity\Recibo 
     */
    public function getUnRecibo()
    {
        return $this->unRecibo;
    }
}