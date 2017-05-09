<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonaEmpleadoType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        if ($options['attr']['tipo'] == 1) {
            $builder
                    ->add('dni', 'integer', array('attr' => array()))
                    ->add('apellido')
                    ->add('nombre')
                    ->add('username',null,array('label'=>'Nombre de Usuario'))
                    ->add('password', 'repeated', array(
                        'type' => 'password',
                        'invalid_message' => 'The password fields must match.',
                        'options' => array('attr' => array('class' => 'password-field')),
                        'required' => true,
                        'first_options'  => array('attr'=>array('tipo'=>'direccion'),'label' => 'Contraseña'),
                        'second_options' => array('attr'=>array('tipo'=>'direccion'),'label' => 'Repita contraseña')))
                    ->add('role', 'entity', array('class' => "SisGG\FinalBundle\Entity\Rol", 'query_builder' => function ($repository) {
                                return $repository->createQueryBuilder('p')->where('p.activo=1');
                            }))
                    ->add('foto',new ImageType())
                    ->add('email', 'email', array('required' => false))
                    ->add('cargoAct', new CargoEmpleadoType())
                    ->add('direccion', new DireccionType())
                    ->add('fechaNac', 'date', array('attr'=>array('ayuda'=>'dd/mm/aaaa'),'format' => 'dd/MM/yyyy', 'label' => 'Fecha de nacimiento', 'widget' => 'single_text'))
                    ->add('primerPago', 'date', array('attr'=>array('ayuda'=>'dd/mm/aaaa'),'format' => 'dd/MM/yyyy', 'label' => 'Primer pago', 'widget' => 'single_text', 'required' => 'required'))
                    ->add('fechaIngreso', 'date', array('attr'=>array('ayuda'=>'dd/mm/aaaa'),'format' => 'dd/MM/yyyy', 'label' => 'Fecha de ingreso', 'widget' => 'single_text', 'required' => 'required'))
                    ->add('sexo', 'choice', array('label' => 'Sexo', 'choices' => array(true => 'Masculino', false => 'Femenino')))
                    ->add('telefonos', 'collection', array('type' => new TelefonoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
            ;
        } elseif ($options['attr']['tipo'] == 2) {
            $empleado = $options['attr']['empleado'];
            if ($empleado->getPrimerPago() == null) {
                $builder
                        ->add('dni', 'integer', array('attr' => array()))
                        ->add('apellido')
                        ->add('nombre')
                        ->add('foto',new ImageType())
                        ->add('email', 'email', array('required' => false))
                        ->add('direccion', new DireccionType())
                        ->add('fechaNac', 'date', array('attr'=>array('ayuda'=>'dd/mm/aaaa'),'format' => 'dd/MM/yyyy', 'label' => 'Fecha de nacimiento', 'widget' => 'single_text'))
                        ->add('sexo', 'choice', array('label' => 'Sexo', 'choices' => array(true => 'Masculino', false => 'Femenino')))
                        ->add('telefonos', 'collection', array('type' => new TelefonoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                ;
            } else {
                $builder
                        
                        ->add('dni', 'integer', array('attr' => array()))
                        ->add('apellido')
                        ->add('nombre')
                        ->add('foto',new ImageType())
                        ->add('email', 'email', array('required' => false))
                        ->add('cargoAct', new CargoEmpleadoType())
                        ->add('direccion', new DireccionType())
                        ->add('fechaNac', 'date', array('attr'=>array('ayuda'=>'dd/mm/aaaa'),'format' => 'dd/MM/yyyy', 'label' => 'Fecha de nacimiento', 'widget' => 'single_text'))
                        ->add('primerPago', 'date', array('attr'=>array('ayuda'=>'dd/mm/aaaa'),'format' => 'dd/MM/yyyy', 'label' => 'Primer pago', 'widget' => 'single_text', 'required' => 'required'))
                        ->add('fechaIngreso', 'date', array('attr'=>array('ayuda'=>'dd/mm/aaaa'),'format' => 'dd/MM/yyyy', 'label' => 'Fecha de ingreso', 'widget' => 'single_text', 'required' => 'required'))
                        ->add('sexo', 'choice', array('label' => 'Sexo', 'choices' => array(true => 'Masculino', false => 'Femenino')))
                        ->add('telefonos', 'collection', array('type' => new TelefonoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
                ;
            }
        } elseif ($options['attr']['tipo'] == 3) {
            $builder
                    ->add('dni', 'integer', array('read_only' => true, 'attr' => array()))
                    ->add('apellido')
                    ->add('nombre')
                    ->add('foto',new ImageType())
                    ->add('email', 'email', array('required' => false, 'read_only' => true))
                    ->add('cargoAct', new CargoEmpleadoType())
                    ->add('direccion', new DireccionType(), array('read_only' => true))
                    ->add('fechaNac', 'date', array('read_only' => true, 'format' => 'dd/MM/yyyy', 'label' => 'Fecha de nacimiento', 'widget' => 'single_text'))
                    ->add('primerPago', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Primer pago', 'widget' => 'single_text', 'required' => 'required'))
                    ->add('fechaIngreso', 'date', array('read_only' => true, 'format' => 'dd/MM/yyyy', 'label' => 'Fecha de ingreso', 'widget' => 'single_text', 'required' => 'required'))
                    ->add('sexo', 'choice', array('read_only' => true, 'label' => 'Sexo', 'choices' => array(true => 'Masculino', false => 'Femenino')))
                    ->add('telefonos', 'collection', array('read_only' => true, 'type' => new TelefonoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))
            ;
        } elseif ($options['attr']['tipo'] == 4) {
           $builder
            ->add('apellido')
            ->add('nombre')
            ->add('email')   
            ->add('username',null,array('label'=>'Nombre de Usuario'))
            ->add('role', 'entity', array('label'=>'Rol','class' => "SisGG\FinalBundle\Entity\Rol", 'query_builder' => function ($repository) {
                        return $repository->createQueryBuilder('p');
                    })) 
              //->add('foto',new ImageType())
                //->add('cargoAct', new CargoEmpleadoType())
                //->add('direccion', new DireccionType(), array('read_only' => true))
//                ->add('fechaNac', 'date', array('read_only' => true, 'format' => 'dd/MM/yyyy', 'label' => 'Fecha de nacimiento', 'widget' => 'single_text'))
//                ->add('primerPago', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Primer pago', 'widget' => 'single_text', 'required' => 'required'))
//                ->add('fechaIngreso', 'date', array('read_only' => true, 'format' => 'dd/MM/yyyy', 'label' => 'Fecha de ingreso', 'widget' => 'single_text', 'required' => 'required'))
                ->add('sexo', 'choice', array('read_only' => true, 'label' => 'Sexo', 'choices' => array(true => 'Masculino', false => 'Femenino')))
                //->add('telefonos', 'collection', array('read_only' => true, 'type' => new TelefonoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))

            ;
        } elseif ($options['attr']['tipo'] == 5) {
           $builder
                ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('attr'=>array('tipo'=>'direccion'),'label' => 'Contraseña'),
                'second_options' => array('attr'=>array('tipo'=>'direccion'),'label' => 'Repita contraseña')))
              ->add('sexo', 'choice', array('read_only' => true, 'label' => 'Sexo', 'choices' => array(true => 'Masculino', false => 'Femenino')))
                //->add('telefonos', 'collection', array('read_only' => true, 'type' => new TelefonoType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => true))

            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\PersonaEmpleado'
        ));
    }

    public function getName() {
        return 'sisgg_finalbundle_personaempleadotype';
    }

}
