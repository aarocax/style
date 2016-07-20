<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class ScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('scheduleDate', 'date', array(
                'label' => 'Schedule Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                //'format' => 'dd-MM-yyyy',
                'input'  => 'datetime',
            ))
            ->add('startingHour', null, array(
                'label' => 'Star Hour',
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control input-sm',
                    ),
                
            ))
            ->add('finishHour', null, array(
                'label' => 'End Hour',
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control input-sm',
                    ),
            ))
            ->add('services', null, array(
                'mapped' => false,
                'label' => 'services',
                'attr' => array(
                    'class'=>'autocompleteservice ui-autocomplete-input form-control input-sm',
                    'data-id' => 'appbundle_appointment_schedules___name___service',
                    'data-url' => '/style.local/web/app_dev.php/service/get-services',
                    'autocomplete' => 'off'
                )
            ))
            ->add('service', 'autocomplete_entity', array(
                'class'        => 'AppBundle\Entity\Service',
                'update_route' => 'service_get',
                'label'        => 'service',
                'constraints'  => new NotNull(),
            ))
            ->add('staffs', null, array(
                'mapped' => false,
                'label' => 'staffs',
                'attr' => array(
                    'class'=>'autocompletestaff ui-autocomplete-input form-control input-sm',
                    'data-id' => 'appbundle_appointment_schedules___name___staff',
                    'data-url' => '/style.local/web/app_dev.php/staff/get-staffs',
                    'autocomplete' => 'off'
                )
            ))
            ->add('staff', 'autocomplete_entity', array(
                'class'        => 'AppBundle\Entity\Staff',
                'update_route' => 'staff_get',
                'label'        => 'staff',
                'constraints'  => new NotNull(),
            ))
            ->add('rooms', null, array(
                'mapped' => false,
                'label' => 'rooms',
                'attr' => array(
                    'class'=>'autocompleteroom ui-autocomplete-input form-control input-sm',
                    'data-id' => 'appbundle_appointment_schedules___name___room',
                    'data-url' => '/style.local/web/app_dev.php/room/get-rooms',
                    'autocomplete' => 'off'
                )
            ))
            ->add('room', 'autocomplete_entity', array(
                'class'        => 'AppBundle\Entity\Room',
                'update_route' => 'room_get',
                'label'        => 'room',
                'constraints'  => new NotNull(),
            ))
            ->add('discount', null, array(
                'label' => 'S. off',
                'attr' => array(
                    'class' => 'form-control input-sm',
                    ),

            ))
            ->add('price', null, array(
                'label' => 'Price',
                'attr' => array(
                    'class' => 'form-control input-sm',
                    ),
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Schedule',
        ));
    }

    public function getName()
    {
        return 'appbundle_schedule';
    }
}