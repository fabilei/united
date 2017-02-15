<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MovimentacaoCota;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Movimentacaocota controller.
 *
 * @Route("movimentacaocota")
 */
class MovimentacaoCotaController extends Controller
{
    /**
     * Lists all Movimentacaocota entities.
     *
     * @Route("/", name="movimentacaocota_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $movimentacaoCotas = $em->getRepository('AppBundle:MovimentacaoCota')->findAll();

        return $this->render('movimentacaocota/index.html.twig', array(
            'movimentacaoCotas' => $movimentacaoCotas,
        ));
    }

    /**
     * Creates a new Movimentacaocota entity.
     *
     * @Route("/new", name="movimentacaocota_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        //compra
        $MovimentacaocotaCompra = new Movimentacaocota();
        $formCompra = $this->createForm('AppBundle\Form\MovimentacaoCotaType', $MovimentacaocotaCompra);
        $formCompra->handleRequest($request);

        //venda
        $MovimentacaocotaVenda = new Movimentacaocota();
        $formVenda = $this->createForm('AppBundle\Form\MovimentacaoCotaType', $MovimentacaocotaVenda);
        $formVenda->handleRequest($request);

        if ($formCompra->isSubmitted() && $formCompra->isValid()
            || $formVenda->isSubmitted() && $formVenda->isValid()) 
        {
            //compra
            $compra = $this->getDoctrine()->getManager();
            $compra->persist($MovimentacaocotaCompra);
            $compra->flush($MovimentacaocotaCompra);

            //venda
            $venda = $this->getDoctrine()->getManager();
            $venda->persist($MovimentacaocotaVenda);
            $venda->flush($MovimentacaocotaVenda);

            return $this->render('movimentacaocota/new.html.twig', array(
                'MovimentacaocotaCompra' => $MovimentacaocotaCompra,
                'MovimentacaocotaVenda' => $MovimentacaocotaVenda,
                'formCompra' => $formCompra->createView(),
                'formVenda' => $formVenda->createView(),
        ));
        }

        return $this->render('movimentacaocota/new.html.twig', array(
            'MovimentacaocotaCompra' => $MovimentacaocotaCompra,
            'MovimentacaocotaVenda' => $MovimentacaocotaVenda,
            'formCompra' => $formCompra->createView(),
            'formVenda' => $formVenda->createView(),
        ));
    }

    /**
     * Finds and displays a Movimentacaocota entity.
     *
     * @Route("/{id}", name="movimentacaocota_show")
     * @Method("GET")
     */
    public function showAction(MovimentacaoCota $Movimentacaocota)
    {
        $deleteForm = $this->createDeleteForm($Movimentacaocota);

        return $this->render('movimentacaocota/show.html.twig', array(
            'Movimentacaocota' => $Movimentacaocota,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Movimentacaocota entity.
     *
     * @Route("/{id}/edit", name="movimentacaocota_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MovimentacaoCota $Movimentacaocota)
    {
        $deleteForm = $this->createDeleteForm($Movimentacaocota);
        $editForm = $this->createForm('AppBundle\Form\MovimentacaoCotaType', $Movimentacaocota);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movimentacaocota_edit', array('id' => $Movimentacaocota->getId()));
        }

        return $this->render('movimentacaocota/edit.html.twig', array(
            'Movimentacaocota' => $Movimentacaocota,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Movimentacaocota entity.
     *
     * @Route("/{id}", name="movimentacaocota_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MovimentacaoCota $Movimentacaocota)
    {
        $form = $this->createDeleteForm($Movimentacaocota);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Movimentacaocota);
            $em->flush($Movimentacaocota);
        }

        return $this->redirectToRoute('movimentacaocota_index');
    }

    /**
     * Creates a form to delete a Movimentacaocota entity.
     *
     * @param MovimentacaoCota $Movimentacaocota The Movimentacaocota entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MovimentacaoCota $Movimentacaocota)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('movimentacaocota_delete', array('id' => $Movimentacaocota->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
