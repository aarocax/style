<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Service;
use AppBundle\Form\ServiceType;

/**
 * Service controller.
 *
 * @Route("/service")
 */
class ServiceController extends Controller
{

    /**
     * Lists all Service entities.
     *
     * @Route("/", name="service")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $services = $em->getRepository('AppBundle:Service')->findAll();

        return $this->render('Service/index.html.twig', array('services' => $services));
    }

    /**
     * Get a Service entity.
     *
     * @Route("/get-service-by-id/{id}", requirements={"id" = "\d+"}, name="service_by_id")
     * @Method("GET")
     */
    public function getServiceByIdAction(Service $service)
    {
        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository('AppBundle:Service')->find($service->getId());
        
        return new JsonResponse(array('name' => $result->getName()));

    }

    /**
     * Displays a form to create and persist a new Service entity.
     *
     * @Route("/new", name="service_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $service = new Service();
        $form   = $this->createCreateForm($service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();
            return $this->redirect($this->generateUrl('service_show', array('id' => $service->getId())));
        }

        return $this->render('Service/new.html.twig', array('service' => $service, 'form'   => $form->createView()));
    }

    /**
     * Creates a form to create a Service entity.
     *
     * @param Service $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Service $service)
    {
        $form = $this->createForm(new ServiceType(), $service, array(
            'action' => $this->generateUrl('service_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Finds and displays a Service entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="service_show")
     * @Method("GET")
     */
    public function showAction(Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);

        return $this->render('Service/show.html.twig', array(
            'service' => $service,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Service entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="service_edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction(Service $service, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($service);
        $deleteForm = $this->createDeleteForm($service);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('service_edit', array('id' => $service->getId())));
        }

        return $this->render('Service/edit.html.twig', array(
            'service'    => $service,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Service entity.
    *
    * @param Service $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Service $service)
    {
        $form = $this->createForm(new ServiceType(), $service, array(
            'action' => $this->generateUrl('service_edit', array('id' => $service->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Service entity.
     *
     * @Route("/{id}", name="service_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Service $service)
    {
        $form = $this->createDeleteForm($service);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($service);
            $em->flush();
        }

        return $this->redirectToRoute('service');
    }

    /**
     * Creates a form to delete a Service entity by id.
     *
     * @param Service $service
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Service $service)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('service_delete', array('id' => $service->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/get-services", name="service_get")
     */
    public function getServiceAction(Request $request)
    {
        $term = $request->query->get('term', null);
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository('AppBundle:Service')->findByTerm($term);
        return new JsonResponse($services);
    }
}
