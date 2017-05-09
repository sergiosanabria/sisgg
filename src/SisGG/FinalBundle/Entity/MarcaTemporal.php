<?php

namespace SisGG\FinalBundle\Entity;

use SisGG\FinalBundle\Entity\Pedido;
use SisGG\FinalBundle\Entity\Cocina;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of MarcaTemporal
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\MarcaTemporalRepository")
 */
class MarcaTemporal implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cocina",inversedBy="marcasTemporales",cascade="persist")  
     */
    private $cocina;

    /**
     *  @ORM\Column(type="string")
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     */
    private $minutos;

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
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return MarcaTemporal
     */
    public function setColor($color) {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * Set minutos
     *
     * @param integer $minutos
     * @return MarcaTemporal
     */
    public function setMinutos($minutos) {
        $this->minutos = $minutos;

        return $this;
    }

    /**
     * Get minutos
     *
     * @return integer 
     */
    public function getMinutos() {
        return $this->minutos;
    }

    /**
     * Set cocina
     *
     * @param \SisGG\FinalBundle\Entity\Cocina $cocina
     * @return MarcaTemporal
     */
    public function setCocina(\SisGG\FinalBundle\Entity\Cocina $cocina = null) {
        $this->cocina = $cocina;

        return $this;
    }

    /**
     * Get cocina
     *
     * @return \SisGG\FinalBundle\Entity\Cocina 
     */
    public function getCocina() {
        return $this->cocina;
    }

    public function HTMLToRGB() {
        $htmlCode = $this->getColor();
        if ($htmlCode[0] == '#')
            $htmlCode = substr($htmlCode, 1);

        if (strlen($htmlCode) == 3) {
            $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
        }

        $r = hexdec($htmlCode[0] . $htmlCode[1]);
        $g = hexdec($htmlCode[2] . $htmlCode[3]);
        $b = hexdec($htmlCode[4] . $htmlCode[5]);

        return $b + ($g << 0x8) + ($r << 0x10);
    }

    function RGBToHSL() {
        $RGB = $this->HTMLToRGB();
        $r = 0xFF & ($RGB >> 0x10);
        $g = 0xFF & ($RGB >> 0x8);
        $b = 0xFF & $RGB;

        $r = ((float) $r) / 255.0;
        $g = ((float) $g) / 255.0;
        $b = ((float) $b) / 255.0;

        $maxC = max($r, $g, $b);
        $minC = min($r, $g, $b);

        $l = ($maxC + $minC) / 2.0;

        if ($maxC == $minC) {
            $s = 0;
            $h = 0;
        } else {
            if ($l < .5) {
                $s = ($maxC - $minC) / ($maxC + $minC);
            } else {
                $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
            }
            if ($r == $maxC)
                $h = ($g - $b) / ($maxC - $minC);
            if ($g == $maxC)
                $h = 2.0 + ($b - $r) / ($maxC - $minC);
            if ($b == $maxC)
                $h = 4.0 + ($r - $g) / ($maxC - $minC);

            $h = $h / 6.0;
        }

        $h = (int) round(255.0 * $h);
        $s = (int) round(100.0 * $s);
        $l = (int) round(100.0 * $l);

        $HSL = array("h"=>$h,"s"=>$s,"l"=>$l);
        return $HSL;
    }
    
    

}