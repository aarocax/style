<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Schedule;
use AppBundle\Entity\Appointment;


/**
 * Controller used to manage blog contents in the public part of the site.
 *
 * @Route("/")
 *
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/cash", name="cash")
     * @Method({"GET", "POST"})
     */
    public function cashAction(Request $request)
    {
      $method = $request->getMethod(); 

      if ($method === 'GET') {
        $date = new \Datetime('now');
        $date = $date->format('Y-m-d');
      } else {
        $date = $request->request->get('date', null);  
      }

      $em = $this->getDoctrine()->getManager();
      (is_null($date)) ? $date = new \Datetime('now') : $date = new \Datetime($date);
      $appointments = $em->getRepository('AppBundle:Appointment')
          ->getAppointmentsOfDay($date->format('Y-m-d').' 00:00:00');
      return $this->render('default/cash.html.twig', array(
            'appointments' => $appointments,
            'date' => $date->format('Y-m-d'),
        ));
    }

   
}
