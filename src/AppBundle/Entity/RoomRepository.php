<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RoomRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoomRepository extends EntityRepository
{
	public function findByTerm($term)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT room.id as id,
                   room.name as label
            FROM AppBundle:Room room
            WHERE room.name LIKE :term
        ")->setParameter('term', '%' . $term . '%');
        return $query->getArrayResult();
    }

    public function findRooms($date)
    {
        (is_null($date)) ? $date = new \Datetime : null;
        $query = $this->getEntityManager()->createQuery("
            SELECT room.id as id,
                   room.name as name,
                   room.description as description
            FROM AppBundle:Room room
            ORDER BY name");

        return $query->getResult();
    }

    
}
