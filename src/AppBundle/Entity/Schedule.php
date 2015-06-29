<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Schedule
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ScheduleRepository")
 */
class Schedule
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
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="scheduleDate", type="datetime")
     */
    private $scheduleDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startingHour", type="time")
     */
    private $startingHour;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finishHour", type="time")
     */
    private $finishHour;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Service", inversedBy="schedules")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Staff", inversedBy="schedules")
     * @ORM\JoinColumn(name="staff_id", referencedColumnName="id")
     */
    private $staff;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Room", inversedBy="schedules")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Appointment", inversedBy="schedules")
     * @ORM\JoinColumn(name="appointment_id", referencedColumnName="id")
     */
    protected $appointment;

    /**
     * @var integer
     *
     * @ORM\Column(name="discount", type="integer")
     */
    private $discount;


    public function __construct()
    {
        $this->creationDate = new \DateTime();
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
     * @return Schedule
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
     * Set scheduleDate
     *
     * @param \DateTime $scheduleDate
     * @return Schedule
     */
    public function setScheduleDate($scheduleDate)
    {
        $this->scheduleDate = $scheduleDate;

        return $this;
    }

    /**
     * Get scheduleDate
     *
     * @return \DateTime 
     */
    public function getScheduleDate()
    {
        return $this->scheduleDate;
    }

    /**
     * Set startingHour
     *
     * @param \DateTime $startingHour
     * @return Schedule
     */
    public function setStartingHour($startingHour)
    {
        $this->startingHour = $startingHour;

        return $this;
    }

    /**
     * Get startingHour
     *
     * @return \DateTime 
     */
    public function getStartingHour()
    {
        return $this->startingHour;
    }

    /**
     * Set finishHour
     *
     * @param \DateTime $finishHour
     * @return Schedule
     */
    public function setFinishHour($finishHour)
    {
        $this->finishHour = $finishHour;

        return $this;
    }

    /**
     * Get finishHour
     *
     * @return \DateTime 
     */
    public function getFinishHour()
    {
        return $this->finishHour;
    }

    /**
     * Set service
     *
     * @param  \AppBundle\Entity\Service $service
     * @return Schedule
     */
    public function setService(\AppBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \AppBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set staff
     *
     * @param  \AppBundle\Entity\Staff $staff
     * @return Schedule
     */
    public function setStaff(\AppBundle\Entity\Staff $staff = null)
    {
        $this->staff = $staff;

        return $this;
    }

    /**
     * Get staff
     *
     * @return \AppBundle\Entity\Staff
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * Set room
     *
     * @param  \AppBundle\Entity\Room $room
     * @return Schedule
     */
    public function setRoom(\AppBundle\Entity\Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \AppBundle\Entity\Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return Schedule
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer 
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set appointment
     *
     * @param  \AppBundle\Entity\Appointment $appointment
     * @return Schedule
     */
    public function setAppointment(\AppBundle\Entity\Appointment $appointment = null)
    {
        $this->appointment = $appointment;

        return $this;
    }

    /**
     * Get appointment
     *
     * @return \AppBundle\Entity\Appointment
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

}
