<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Room;
use AppBundle\Form\RoomType;


/**
 * Room controller.
 *
 * @Route("/room")
 */
class RoomController extends Controller
{
    /**
     * Lists all Room entities.
     *
     * @Route("/", name="room")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rooms = $em->getRepository('AppBundle:Room')->findAll();

        return $this->render('Room/index.html.twig', array('rooms' => $rooms));
    }

    /**
     * Displays a form to create and persist a new Room entity.
     *
     * @Route("/new", name="room_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $room = new Room();
        $form   = $this->createCreateForm($room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();
            return $this->redirect($this->generateUrl('room_show', array('id' => $room->getId())));
        }

        return $this->render('Room/new.html.twig', array('room' => $room, 'form'   => $form->createView()));
    }

    /**
     * Finds and a Room entity.
     *
     * @Route("/get-room-by-id/{id}", requirements={"id" = "\d+"}, name="room_by_id")
     * @Method("GET")
     */
    public function getRoomByIdAction(Room $room)
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('AppBundle:Room')->find($room->getId());
        return new JsonResponse(array('name' => $result->getName()));
    }

    /**
     * Creates a form to create a Room entity.
     *
     * @param Room $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Room $room)
    {
        $form = $this->createForm(new RoomType(), $room, array(
            'action' => $this->generateUrl('room_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }


    /**
     * Finds and displays a Room entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="room_show")
     * @Method("GET")
     */
    public function showAction(Room $room)
    {
        $deleteForm = $this->createDeleteForm($room);

        return $this->render('Room/show.html.twig', array(
            'room' => $room,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Room entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="room_edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction(Room $room, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($room);
        $deleteForm = $this->createDeleteForm($room);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('room_edit', array('id' => $room->getId())));
        }

        return $this->render('Room/edit.html.twig', array(
            'room'    => $room,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Room entity.
    *
    * @param Room $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Room $room)
    {
        $form = $this->createForm(new RoomType(), $room, array(
            'action' => $this->generateUrl('room_edit', array('id' => $room->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Creates a form to delete a Room entity by id.
     *
     * @param Room $room
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Room $room)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('room_delete', array('id' => $room->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a Room entity.
     *
     * @Route("/{id}", name="room_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Room $room)
    {
        $form = $this->createDeleteForm($room);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($room);
            $em->flush();
        }

        return $this->redirectToRoute('room');
    }

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