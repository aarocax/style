<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Staff;
use AppBundle\Form\StaffType;

/**
 * Staff controller.
 *
 * @Route("/staff")
 */
class StaffController extends Controller
{

    /**
     * Lists all Staff entities.
     *
     * @Route("/", name="staff")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $staffs = $em->getRepository('AppBundle:Staff')->findAll();
        return $this->render('Staff/index.html.twig', array('staffs' => $staffs));
    }

    /**
     * Finds and a Satff entity.
     *
     * @Route("/get-staff-by-id/{id}", requirements={"id" = "\d+"}, name="staff_by_id")
     * @Method("GET")
     */
    public function getStaffByIdAction(Staff $staff)
    {
        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository('AppBundle:Staff')->find($staff->getId());
        
        return new JsonResponse(array('name' => $result->getName(), 'lastName' =>  $result->getLastName()));

    }

    /**
     * Displays a form to create and persist a new Staff entity.
     *
     * @Route("/new", name="staff_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $staff = new Staff();
        $form   = $this->createCreateForm($staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($staff);
            $em->flush();
            return $this->redirect($this->generateUrl('staff_show', array('id' => $staff->getId())));
        }

        return $this->render('Staff/new.html.twig', array('staff' => $staff, 'form'   => $form->createView()));
    }

    /**
     * Finds and displays a Staff entity.
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="staff_show")
     * @Method("GET")
     */
     public function showAction(Staff $staff)
    {
        $deleteForm = $this->createDeleteForm($staff);

        return $this->render('Staff/show.html.twig', array(
            'staff'        => $staff,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Staff entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="staff_edit")
     * @Method({"GET", "PUT"})
     */
    public function editAction(Staff $staff, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($staff);
        $deleteForm = $this->createDeleteForm($staff);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            return $this->redirectToRoute('staff_show', array('id' => $staff->getId()));
        }

        return $this->render('Staff/edit.html.twig', array(
            'staff'        => $staff,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to create a Staff entity.
     *
     * @param Staff $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Staff $staff)
    {
        $form = $this->createForm(new StaffType(), $staff, array(
            'action' => $this->generateUrl('staff_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }



   /**
    * Creates a form to edit a Staff entity.
    *
    * @param Staff $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Staff $staff)
    {
        $form = $this->createForm(new StaffType(), $staff, array(
            'action' => $this->generateUrl('staff_edit', array('id' => $staff->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Staff entity.
     *
     * @Route("/{id}", name="staff_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Staff')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Staff entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('staff'));
    }

    /**
     * Creates a form to delete a Staff entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Staff $staff)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('staff_delete', array('id' => $staff->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @Route("/get-staffs", name="staff_get")
     */
    public function getStaffAction(Request $request)
    {
        $term = $request->query->get('term', null);
        $em = $this->getDoctrine()->getManager();
        $customers = $em->getRepository('AppBundle:Staff')->findByTerm($term);
        return new JsonResponse($customers);
    }
}
