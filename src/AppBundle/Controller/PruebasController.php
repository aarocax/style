<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Customer;
use AppBundle\Form\Pruebas\Prueba1Type;



/**
 * Pruebas controller.
 *
 * @Route("/pruebas")
 */
class PruebasController extends Controller
{
	/**
     * Show pruebas.
     *
     * @Route("/", name="pruebas")
     * @Method("GET")
     */
    public function indexAction()
    {
        
        return $this->render('Pruebas/index.html.twig');
    }

    /**
     * Displays a form to create and persist a new Customer entity.
     *
     * @Route("/new", name="pruebas_customer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $customer = new Customer();
        $form   = $this->createCreateForm($customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();
            return $this->redirect($this->generateUrl('customer_show', array('id' => $customer->getId())));
        }

        return $this->render('Pruebas/new.html.twig', array('customer' => $customer, 'form'   => $form->createView()));
    }

    /**
     * Creates a form to create a Customer entity.
     *
     * @param Customer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Customer $customer)
    {
        $form = $this->createForm(new Prueba1Type(), $customer, array(
            'action' => $this->generateUrl('pruebas_customer_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }
}