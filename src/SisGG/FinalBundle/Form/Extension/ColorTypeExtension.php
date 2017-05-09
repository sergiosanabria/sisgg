<?php
namespace SisGG\FinalBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
/**
 * Description of ColorTypeExtension
 *
 * @author martin
 */
class ColorTypeExtension  extends AbstractTypeExtension{
    
    /**
     * @return string
     */
    public function getExtendedType() {
        return 'text';
    }
    
}

?>
