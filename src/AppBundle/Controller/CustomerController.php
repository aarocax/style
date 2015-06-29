<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerType;

/**
 * Customer controller.
 *
 * @Route("/customer")
 */
class CustomerController extends Controller
{

    /**
     * Lists all Customer entities.
     *
     * @Route("/", name="customer")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $customers = $em->getRepository('AppBundle:Customer')->findAll();

        return $this->render('Customer/index.html.twig', array('customers' => $customers));
    }

    /**
     * Displays a form to create and persist a new Customer entity.
     *
     * @Route("/new", name="customer_new")
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

        return $this->render('Customer/new.html.twig', array('customer' => $customer, 'form'   => $form->createView()));
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
        $form = $this->createForm(new CustomerType(), $customer, array(
            'action' => $this->generateUrl('customer_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a Customer entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="customer_show")
     * @Method("GET")
     */
    public function showAction(Customer $customer)
    {
        $deleteForm = $this->createDeleteForm($customer);

        return $this->render('Customer/show.html.twig', array(
            'customer' => $customer,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing Customer entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="customer_edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction(Customer $customer, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($customer);
        $deleteForm = $this->createDeleteForm($customer);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('customer_edit', array('id' => $customer->getId())));
        }

        return $this->render('Customer/edit.html.twig', array(
            'customer'    => $customer,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Customer entity.
    *
    * @param Customer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Customer $customer)
    {
        $form = $this->createForm(new CustomerType(), $customer, array(
            'action' => $this->generateUrl('customer_edit', array('id' => $customer->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Customer entity.
     *
     * @Route("/{id}", name="customer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Customer $customer)
    {
        $form = $this->createDeleteForm($customer);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($customer);
            $em->flush();
        }

        return $this->redirectToRoute('customer');
    }

    /**
     * Creates a form to delete a Customer entity by id.
     *
     * @param Customer $customer
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Customer $customer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer_delete', array('id' => $customer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/get-customers", name="customer_get")
     */
    public function getPersonsAction(Request $request)
    {
        $term = $request->query->get('term', null);
        $em = $this->getDoctrine()->getManager();
        $customers = $em->getRepository('AppBundle:Customer')->findByTerm($term);
        return new JsonResponse($customers);
    }
}
