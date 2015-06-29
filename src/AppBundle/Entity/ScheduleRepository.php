<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ScheduleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScheduleRepository extends EntityRepository
{
	public function getRoomsSchedulesWeek($firstDayOfWeek, $lastDayOfWeek, $room_id)
    {
        (is_null($firstDayOfWeek)) ? $date = new \Datetime : null;
        (is_null($lastDayOfWeek)) ? $date = new \Datetime : null;
        $query = $this->getEntityManager()->createQuery("
            SELECT s.id as id,
                   s.scheduleDate as scheduleDate,
                   s.startingHour as startingHour,
                   s.finishHour as finishHour,
                   r.description as room,
                   sv.description as service,
                   stf.name as staff,
                   a.id as appointment,
                   c.name as name,
                   c.lastName as lastName
            FROM AppBundle:Schedule s
            JOIN s.room r
            JOIN s.service sv
            JOIN s.appointment a
            JOIN a.customer c
            LEFT JOIN s.staff stf
            WHERE s.scheduleDate >= :monday
            AND s.scheduleDate <= :sunday
            AND s.room = :room_id")
        ->setParameter('monday', $firstDayOfWeek)
        ->setParameter('sunday', $lastDayOfWeek)
        ->setParameter('room_id', $room_id);

        return $query->getResult();
    }

    public function getSchedulesPresentDay($date, $room_id)
    {
        (is_null($date)) ? $date = new \Datetime : null;
        $query = $this->getEntityManager()->createQuery("
            SELECT s.id as id,
                   s.scheduleDate as scheduleDate,
                   s.startingHour as startingHour,
                   s.finishHour as finishHour,
                   r.description as room,
                   sv.description as service,
                   stf.name as staff,
                   a.id as appointment,
                   c.name as name,
                   c.lastName as lastName
            FROM AppBundle:Schedule s
            JOIN s.room r
            JOIN s.service sv
            JOIN s.appointment a
            JOIN a.customer c
            LEFT JOIN s.staff stf
            WHERE s.scheduleDate = :date
            AND s.room = :room_id")
        ->setParameter('date', $date)
        ->setParameter('room_id', $room_id);

        return $query->getResult();
    }
}