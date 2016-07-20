<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Appointment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AppointmentRepository")
 */
class Appointment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="appointment_date", type="datetime")
     */
    private $appointmentDate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="appointments")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Schedule", mappedBy="appointment", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    protected $schedules;

    /**
     * @var boolean $paid
     *
     * @ORM\Column(name="paid", type="boolean", nullable=true)
     * @Assert\Type(type="bool")
     */
    private $paid;

    /**
     * @var float
     *
     * @ORM\Column(name="debt", type="decimal", scale=2, nullable=true)
     */
    private $debt;



    /**
     * Constructor
     */
    public function __construct()
    {
      $this->creationDate = new \DateTime();
      $this->schedules = new \Doctrine\Common\Collections\ArrayCollection();
      $this->debt = 0.0;
      $this->paid = FALSE;
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Appointment
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set appointmentDate
     *
     * @param \DateTime $appointmentDate
     * @return Appointment
     */
    public function setAppointmentDate($appointmentDate)
    {
        $this->appointmentDate = $appointmentDate;

        return $this;
    }

    /**
     * Get appointmentDate
     *
     * @return \DateTime 
     */
    public function getAppointmentDate()
    {
        return $this->appointmentDate;
    }

    /**
     * Set customer
     *
     * @param  \AppBundle\Entity\Customer $customer
     * @return Appointment
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * set schedules
     *
     * @return \AppBundle\Entity\Appointment
     */
    public function setSchedules(\Doctrine\Common\Collections\Collection $schedules)
    {
        $this->schedules = $schedules;
        foreach ($schedules as $schedule) {
            $schedule->setAppointment($this);
        }
    }

    /**
     * Get schedules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * Set debt
     *
     * @param float $debt
     * @return Appointment
     */
    public function setDebt($debt)
    {
        $this->debt = $debt;

        return $this;
    }

    /**
     * Get debt
     *
     * @return float 
     */
    public function getDebt()
    {
        return $this->debt;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     * @return Appointment
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean 
     */
    public function getPaid()
    {
        return $this->paid;
    }
}
