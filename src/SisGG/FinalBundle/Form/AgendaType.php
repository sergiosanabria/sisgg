<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \DateTime;

class AgendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['attr']['tipo'] == 1) {
        $hoy = new DateTime('now');
        $hoy = date_format($hoy, 'd/m/Y');
        $builder->add('asunto', 'text')
                ->add('lugar', 'text', array('required'=>false))
                ->add('descripcion')
                ->add('etiqueta', 'entity', array('class' => "SisGG\FinalBundle\Entity\EtiquetaAgenda", 'empty_value' => false, 'label'=>'Etiqueta', 'required'=>true))
                ->add('inicioFec', 'date', array('attr'=>array('value'=>$hoy, 'onchange'=>'cambioFecha(this)'),'format' => 'dd/MM/yyyy', 'label' => 'Fecha de inicio', 'widget' => 'single_text', 'required' => 'required'))
                ->add('inicioHora','time', array('attr'=>array('class'=>'inline'),'label' => 'Hora de inicio') )
                ->add('finFec', 'date', array('attr'=>array('value'=>$hoy),'format' => 'dd/MM/yyyy', 'label' => 'Fecha de fin', 'widget' => 'single_text', 'required' => 'required'))
                ->add('finHora','time', array('attr'=>array('class'=>'inline',),'label' => 'Hora de fin') )
                ->add('todos',null, array('label'=>'Todo el día'))

            ;
        }elseif ($options['attr']['tipo'] == 2) {
             $builder->add('asunto', 'text')
                ->add('lugar', 'text', array('required'=>false))
                ->add('descripcion')
                ->add('etiqueta', 'entity', array('class' => "SisGG\FinalBundle\Entity\EtiquetaAgenda", 'empty_value' => 'Seleccione una etiqueta', 'label'=>'Etiqueta', 'required'=>true))
                ->add('inicioFec', 'date', array('attr'=>array( 'onchange'=>'cambioFecha(this)'),'format' => 'dd/MM/yyyy', 'label' => 'Fecha de inicio', 'widget' => 'single_text', 'required' => 'required'))
                ->add('inicioHora','time', array('attr'=>array('class'=>'inline', 'onchange'=>'cambioHora(this)','label' => 'Hora de inicio')) )
                ->add('finFec', 'date', array('format' => 'dd/MM/yyyy', 'label' => 'Fecha de fin', 'widget' => 'single_text', 'required' => 'required'))
                ->add('finHora','time', array('attr'=>array('class'=>'inline','label' => 'Hora de fin')) )
                ->add('todos',null, array('label'=>'Todo el día'))

            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Agenda'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_agendatype';
    }
}
