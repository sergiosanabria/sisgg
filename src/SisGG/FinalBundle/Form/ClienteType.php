<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\Telefono;
use SisGG\FinalBundle\Entity\CuentaCorriente;
use Doctrine\ORM\EntityRepository;

class ClienteType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('telefono', new TelefonoType(), array("empty_data" => new Telefono()))
                ->add('apellido')
                ->add('nombre')
                ->add('tipoDocumento', 'entity', array(
                    'label' => 'Tipo de Documento',
                    'required' => false,
                    'empty_value' => 'Seleccione un Tipo',
                    'class' => 'SisGGFinalBundle:TipoDocumento',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->where("c.estado LIKE 'activo'");
                    },
                ))
                ->add('condicionIva', 'entity', array(
                    'label' => 'Condición',
                    'required' => true,
                    'empty_value' => 'Seleccione una Condicion',
                    'class' => 'SisGGFinalBundle:CondicionIva',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->where("c.estado LIKE 'activo'");
                    },
                ))
                ->add('cuit', null, array('label' => 'C.U.I.T.','required'=>false,'attr' => array('pattern' => "[0-9]{2}[-][0-9]{8}[-][0-9]{1}")))
                ->add('documento', null, array('required' => false))
                ->add('email', null, array('attr' => array('placeholder' => 'example@example')))
                ->add('direccion', new DireccionType(), array("empty_data" => new Direccion()))
                ->add('descuentos', null, array('label'=>"Descuentos",'required' => false))
                ->add('gruposCliente', null, array('label'=>"Grupos de Cliente",'required' => false))
                //->add('cuenta', 'collection', array('type' => new CuentaCorrienteType(), 'allow_add' => true, 'by_reference' => false, 'attr' => array('añadir' => false, 'forma' => 'simple')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Cliente'
        ));
    }

    public function getName() {
        return 'cliente';
    }

}
