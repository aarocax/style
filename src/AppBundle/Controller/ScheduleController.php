<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Schedule;
use AppBundle\Form\ScheduleType;

/**
 * Schedule controller.
 *
 * @Route("/schedule")
 */
class ScheduleController extends Controller
{

    /**
     * Lists all Schedule entities.
     *
     * @Route("/", name="schedule")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $schedules = $em->getRepository('AppBundle:Schedule')->findAll();

        return $this->render('Schedule/index.html.twig', array('schedules' => $schedules));
    }

    /**
     * Displays schedules of day.
     *
     * @Route("/schedules-of-day", name="schedules-of-day")
     * @Method({"GET", "POST"})
     */
    public function getSchedulesOfDayAction(Request $request)
    {
        $date = $request->query->get('date', null);
        $em = $this->getDoctrine()->getManager();
        $schedules = $em->getRepository('AppBundle:Schedule')->getSchedulesOfDay($date);
        return new JsonResponse($schedules);

    }

    /**
     * Displays a form to create and persist a new Schedule entity.
     *
     * @Route("/new", name="schedule_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $schedule = new Schedule();
        $form   = $this->createCreateForm($schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($schedule);
            $em->flush();
            return $this->redirect($this->generateUrl('schedule_show', array('id' => $schedule->getId())));
        }

        return $this->render('Schedule/new.html.twig', array('schedule' => $schedule, 'form'   => $form->createView()));
    }

    /**
     * Verify if exist any schedule at same time.
     *
     * @Route("/exist-schedule-startHour", name="exist-schedule-startHour")
     * @Method({"GET", "POST"})
     */
    public function existScheduleStartHour(Request $request) {
        $startTime = $request->query->get('st', null);
        $endTime = $request->query->get('et', null);
        $room = $request->query->get('room', null);
        $scheduleDate = $request->query->get('scheduleDate', null);

        $em = $this->getDoctrine()->getManager();
        $schedules = $em->getRepository('AppBundle:Schedule')->existScheduleStartHour($room, $scheduleDate, $startTime, $endTime);
        return new JsonResponse($schedules);

    }

    
    /**
     * Verify if exist any schedule at same time.
     *
     * @Route("/exist-schedule-finishHour", name="exist-schedule-finishHour")
     * @Method({"GET", "POST"})
     */
    public function existScheduleFinishHour(Request $request) {
        $startTime = $request->query->get('st', null);
        $endTime = $request->query->get('et', null);
        $room = $request->query->get('room', null);
        $scheduleDate = $request->query->get('scheduleDate', null);

        $customers = array('room'=>$room, 'scheduleDate'=> $scheduleDate, 'startTime'=>$startTime, 'endTime'=>$endTime);
        //ld($customers);

        $em = $this->getDoctrine()->getManager();
        $schedules = $em->getRepository('AppBundle:Schedule')->existScheduleFinishHour($room, $scheduleDate, $startTime, $endTime);
        return new JsonResponse($schedules);
    }

    /**
     * Verify if exist any schedule at same time.
     * This controler is for test
     * @Route("/exist-schedule-inThisHour", name="exist-schedule-inthishour")
     * @Method({"GET", "POST"})
     */
    public function existScheduleInThisHour(Request $request) {
        $startTime = $request->query->get('st', null);
        $endTime = $request->query->get('et', null);
        $room = $request->query->get('room', null);
        $scheduleDate = $request->query->get('scheduleDate', null);

        dump($scheduleDate);

        $customers = array('room'=>$room, 'scheduleDate'=> $scheduleDate, 'startTime'=>$startTime, 'endTime'=>$endTime);
        //ld($customers);

        $em = $this->getDoctrine()->getManager();
        $schedules = $em->getRepository('AppBundle:Schedule')->existScheduleInThisHour($room, $scheduleDate, $startTime, $endTime);
        return new JsonResponse($schedules);
    }
    

    /**
     * Creates a form to create a Schedule entity.
     *
     * @param Schedule $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Schedule $schedule)
    {
        $form = $this->createForm(new ScheduleType(), $schedule, array(
            'action' => $this->generateUrl('schedule_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a Schedule entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="schedule_show")
     * @Method("GET")
     */
    public function showAction(Schedule $schedule)
    {
        $deleteForm = $this->createDeleteForm($schedule);

        return $this->render('Schedule/show.html.twig', array(
            'schedule' => $schedule,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Schedule entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="schedule_edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction(Schedule $schedule, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($schedule);
        $deleteForm = $this->createDeleteForm($schedule);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('schedule_edit', array('id' => $schedule->getId())));
        }

        return $this->render('Schedule/edit.html.twig', array(
            'schedule'    => $schedule,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Schedule entity.
    *
    * @param Schedule $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Schedule $schedule)
    {
        $form = $this->createForm(new ScheduleType(), $schedule, array(
            'action' => $this->generateUrl('schedule_edit', array('id' => $schedule->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    

    /**
     * Deletes a Schedule entity.
     *
     * @Route("/{id}", name="schedule_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Schedule $schedule)
    {
        $form = $this->createDeleteForm($schedule);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($schedule);
            $em->flush();
        }

        return $this->redirectToRoute('schedule');
    }

    /**
     * Creates a form to delete a Schedule entity by id.
     *
     * @param Schedule $schedule
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Schedule $schedule)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('schedule_delete', array('id' => $schedule->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
