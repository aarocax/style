<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Room;


/**
 * Room controller.
 *
 * @Route("/room")
 */
class RoomController extends Controller
{
	/**
     * @Route("/get-rooms", name="room_get")
     */
    public function getServiceAction(Request $request)
    {
        $term = $request->query->get('term', null);
        $em = $this->getDoctrine()->getManager();
        $rooms = $em->getRepository('AppBundle:Room')->findByTerm($term);
        return new JsonResponse($rooms);
    }
}