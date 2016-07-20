<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Appointment;
use AppBundle\Form\AppointmentType;

/**
 * Appointment controller.
 *
 * @Route("/appointment")
 */
class AppointmentController extends Controller
{

    /**
     * Lists all Appointment entities.
     *
     * @Route("/", name="appointment")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $appointments = $em->getRepository('AppBundle:Appointment')->findAll();

        return $this->render('Appointment/index.html.twig', array('appointments' => $appointments));
    }

    /**
     * Displays a form to create and persist a new Appointment entity.
     *
     * @Route("/new", name="appointment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $date=$request->request->get("date");
        $time=$request->request->get("time");
        $room=$request->request->get("room");
        $appointment = new Appointment();
        $form   = $this->createCreateForm($appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($appointment);
            $em->flush();
            return $this->redirect($this->generateUrl('appointment_show', array('id' => $appointment->getId())));
        }

        return $this->render('Appointment/new.html.twig', array(
            'appointment' => $appointment,
            'form'   => $form->createView(),
            'date' => $date,
            'time' => $time,
            'room' => $room,
        ));
    }

    /**
     * Creates a form to create a Appointment entity.
     *
     * @param Appointment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Appointment $appointment)
    {
        $form = $this->createForm(new AppointmentType(), $appointment, array(
            'action' => $this->generateUrl('appointment_new'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    

    /**
     * Finds and displays a appointment entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="appointment_show")
     * @Method("GET")
     */
    public function showAction(Appointment $appointment)
    {
        $deleteForm = $this->createDeleteForm($appointment);

        return $this->render('Appointment/show.html.twig', array(
            'appointment' => $appointment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Appointment entity.
     *
     * @Route("/{id}/edit", name="appointment_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $appointment = $em->getRepository('AppBundle:Appointment')->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('Unable to find Appointment entity.');
        }

        $originalSchedules = array();
        // Create an array of the current Address objects in the database
        foreach ($appointment->getSchedules() as $schedule) {
            $originalSchedules[] = $schedule;
        }

        dump($originalSchedules);

        $editForm = $this->createEditForm($appointment);
        $deleteForm = $this->createDeleteForm($appointment);

       


        dump($appointment);
        dump(count($appointment->getSchedules()));
        dump($appointment->getSchedules());

        return $this->render('Appointment/edit.html.twig',array(
            'appointment'      => $appointment,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Appointment entity.
    *
    * @param Appointment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Appointment $entity)
    {
        $form = $this->createForm(new AppointmentType(), $entity, array(
            'action' => $this->generateUrl('appointment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Appointment entity.
     *
     * @Route("/{id}", name="appointment_update")
     * @Method("PUT")
     * @Template("AppBundle:Appointment:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Appointment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Appointment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('appointment_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Appointment entity.
     *
     * @Route("/{id}", name="appointment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Appointment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Appointment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('appointment'));
    }

    /**
     * Creates a form to delete a Appointment entity by id.
     *
     * @param Appointment $appointment
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Appointment $appointment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('appointment_delete', array('id' => $appointment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
}
