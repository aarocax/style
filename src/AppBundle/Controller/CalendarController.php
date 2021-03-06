<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Form\SearchDateType;

use AppBundle\Model\Calendar;

/**
 * Appointment controller.
 *
 * @Route("/calendar")
 */
class CalendarController extends Controller
{
	/**
     * Show calendar.
     *
     * @Route("/", name="calendar")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $formDate = $this->createForm(new SearchDateType());
        $date = new \Datetime;
        //$fecha->add(date_interval_create_from_date_string('10 days'));
        $interval = 15;
        $calendar = $this->get('calendar');
        $week =  $calendar->makeCalendar($interval, $date);

        $summary = $calendar->summary($week['salas'], $date);

        return $this->render('Calendar/index.html.twig', array(
            'horario' => $week['horario'],
            'date' => $date,
            'salas' => $week['salas'],
            'formDate' => $formDate->createView(),
            'summary' => $summary,
        ));
    }
}