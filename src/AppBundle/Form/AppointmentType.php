<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use AppBundle\Entity\Schedule;

class AppointmentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('appointmentDate', 'date', array(
                'label' => 'fecha',
                'required' => true,
                'format' => 'dd-MM-yyyy',
                'input'  => 'datetime',
            ))
            //->add('date')
            ->add('customer', 'autocomplete_entity', array(
                'class'        => 'AppBundle\Entity\Customer',
                'update_route' => 'customer_get',
                'label'        => 'Customer',
                'constraints'  => new NotNull(),
            ))
            ->add('schedules', 'collection', array(
                'type'           => new ScheduleType(),
                //'label'          => 'Schedules',
                'by_reference'   => false,
                //'prototype_data' => new Address(),
                'allow_delete'   => true,
                'allow_add'      => true,
                'attr'           => array(
                    'class' => 'row schedules'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Appointment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_appointment';
    }
}
