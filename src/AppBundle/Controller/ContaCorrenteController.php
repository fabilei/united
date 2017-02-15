<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContaCorrente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Contacorrente controller.
 *
 * @Route("contacorrente")
 */
class ContaCorrenteController extends Controller
{
    /**
     * Lists all contaCorrente entities.
     *
     * @Route("/", name="contacorrente_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contaCorrentes = $em->getRepository('AppBundle:ContaCorrente')->findAll();

        return $this->render('contacorrente/index.html.twig', array(
            'contaCorrentes' => $contaCorrentes,
        ));
    }

    /**
     * Creates a new contaCorrente entity.
     *
     * @Route("/new", name="contacorrente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contaCorrente = new Contacorrente();
        $form = $this->createForm('AppBundle\Form\ContaCorrenteType', $contaCorrente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contaCorrente);
            $em->flush($contaCorrente);

            return $this->redirectToRoute('contacorrente_show', array('id' => $contaCorrente->getId()));
        }

        return $this->render('contacorrente/new.html.twig', array(
            'contaCorrente' => $contaCorrente,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a contaCorrente entity.
     *
     * @Route("/{id}", name="contacorrente_show")
     * @Method("GET")
     */
    public function showAction(ContaCorrente $contaCorrente)
    {
        $deleteForm = $this->createDeleteForm($contaCorrente);

        return $this->render('contacorrente/show.html.twig', array(
            'contaCorrente' => $contaCorrente,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing contaCorrente entity.
     *
     * @Route("/{id}/edit", name="contacorrente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ContaCorrente $contaCorrente)
    {
        $deleteForm = $this->createDeleteForm($contaCorrente);
        $editForm = $this->createForm('AppBundle\Form\ContaCorrenteType', $contaCorrente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contacorrente_edit', array('id' => $contaCorrente->getId()));
        }

        return $this->render('contacorrente/edit.html.twig', array(
            'contaCorrente' => $contaCorrente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a contaCorrente entity.
     *
     * @Route("/{id}", name="contacorrente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ContaCorrente $contaCorrente)
    {
        $form = $this->createDeleteForm($contaCorrente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contaCorrente);
            $em->flush($contaCorrente);
        }

        return $this->redirectToRoute('contacorrente_index');
    }

    /**
     * Creates a form to delete a contaCorrente entity.
     *
     * @param ContaCorrente $contaCorrente The contaCorrente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ContaCorrente $contaCorrente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contacorrente_delete', array('id' => $contaCorrente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
