<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LivroTransacao;
use AppBundle\Entity\MovimentacaoCota;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Livrotransacao controller.
 *
 * @Route("livrotransacao")
 */
class LivroTransacaoController extends Controller
{
    /**
     * Lists all livroTransacao entities.
     *
     * @Route("/", name="livrotransacao_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $livroTransacaos = $em->getRepository('AppBundle:LivroTransacao')->findAll();

        return $this->render('livrotransacao/index.html.twig', array(
            'livroTransacaos' => $livroTransacaos,
        ));
    }

    /**
     * Creates a new livroTransacao entity.
     *
     * @Route("/new", name="livrotransacao_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        
        //lista transações
        $em = $this->getDoctrine()->getManager();
        $livroTransacaos = $em->getRepository('AppBundle:LivroTransacao')->findAll();

        // compra
        $livroTransacaoCompra = new Livrotransacao();
        $formCompra = $this->createForm('AppBundle\Form\LivroTransacaoType', $livroTransacaoCompra);
        $formCompra->handleRequest($request);

        // venda
        $livroTransacaoVenda = new Livrotransacao();
        $formVenda = $this->createForm('AppBundle\Form\LivroTransacaoVendaType', $livroTransacaoVenda);
        $formVenda->handleRequest($request);

        if ($formCompra->isSubmitted() && $formCompra->isValid()
            || $formVenda->isSubmitted() && $formVenda->isValid()) 
        {
            //compra
            $compra = $this->getDoctrine()->getManager();
            $livroTransacaoVenda->setTipoOperacao(0);
            $compra->persist($livroTransacaoCompra);
            $compra->flush($livroTransacaoCompra);

            //venda
            $venda = $this->getDoctrine()->getManager();
            $livroTransacaoVenda->setTipoOperacao(1);
            $venda->persist($livroTransacaoVenda);
            $venda->flush($livroTransacaoVenda);

            return $this->render('livrotransacao/new.html.twig', array(
                'livroTransacaoCompra' => $livroTransacaoCompra,
                'livroTransacaoVenda' => $livroTransacaoVenda,
                'formCompra' => $formCompra->createView(),
                'formVenda' => $formVenda->createView(),
                'livroTransacaos' => $livroTransacaos,
            ));
        }

        return $this->render('livrotransacao/new.html.twig', array(
            'livroTransacaoCompra' => $livroTransacaoCompra,
            'formCompra' => $formCompra->createView(),
            'formVenda' => $formVenda->createView(),
            'livroTransacaos' => $livroTransacaos,
        ));
    }

/**
 * recebe o post do AJAX de compra
 *
 * @Route("/comprar", name="livrotransacao_comprar")
 * @Method({"GET", "POST"})
 *
 */
public function comprarAction(Request $request)
{
    //This is optional. Do not do this check if you want to call the same action using a regular request.
    if (!$request->isXmlHttpRequest()) 
    {
        return new JsonResponse(array('message' => 'Somente para uso em Ajax!'), 400);
    }

    try
    {
		if($_POST["qtdCotas"] != '' && $_POST["valorCotas"] != '')
		{
			// compra
			$livroTransacaoCompra = new Livrotransacao();
			$livroTransacaoCompra->setTipoOperacao(0);
			$livroTransacaoCompra->setQuantidadeCotas($_POST["qtdCotas"]);
			$livroTransacaoCompra->setValorCotas($_POST["valorCotas"]);
			$livroTransacaoCompra->setStatus(0);
			//gravar código da conta do usuário
			//$livroTransacaoCompra->setConcor(1);

			$compra = $this->getDoctrine()->getManager();
			$compra->persist($livroTransacaoCompra);
			$compra->flush($livroTransacaoCompra);
			
			// movimentação
			$total = $_POST["valorCotas"] * $_POST["qtdCotas"];
			$MovimentacaoCompra = new MovimentacaoCota();
			$MovimentacaoCompra->setIdConta(1);
			$MovimentacaoCompra->setIdContaTerceiro(0);
			$MovimentacaoCompra->setQuantidade($_POST["qtdCotas"]);
			$MovimentacaoCompra->setValorUnitario($_POST["valorCotas"]);
			$MovimentacaoCompra->setValorTotal($total);
			//$MovimentacaoCompra->setConcor(1);

			$movimentacao = $this->getDoctrine()->getManager();
			$movimentacao->persist($MovimentacaoCompra);
			$movimentacao->flush($MovimentacaoCompra);
        
        }
		else
		{
			return new JsonResponse(array(
				'livroTransacaos' => '',
				'retorno' => '',
				'erro' => 'erro'
			), 200);
		}
        
        //código push

        $options = array(
            'encrypted' => true
        );
        $pusher = new \Pusher(
            '939ce1e989ceb6b401b5',
            '84a028efced54d78f241',
            '301127',
            $options
        );

        //lista transações
        $em = $this->getDoctrine()->getManager();
        $livroTransacaos = $em->getRepository('AppBundle:LivroTransacao')->findAllArray();      
        
        $retorno = new JsonResponse($pusher->trigger('my-channel', 'subscription_succeeded', $livroTransacaos), 200);
        //fim código do push do site

        return new JsonResponse(array(
            'livroTransacaos' => $livroTransacaos,
            'retorno' => $retorno,
            'erro' => ''
        ), 200);

 }catch(Exception $ex){
    return new JsonResponse(array('message' => 'erro'), 200); 
 }
 
    return new JsonResponse(array('message' => 'erro'), 200);
}

/**
 * recebe o post do AJAX de atualização
 *
 * @Route("/atualizar", name="atualizar")
 * @Method({"GET", "POST"})
 *
 */
public function atualizarAction(Request $request)
{
    //lista transações
        $em = $this->getDoctrine()->getManager();
        $livroTransacaos = $em->getRepository('AppBundle:LivroTransacao')->findAllArray();      

    return new JsonResponse(array(
            'livroTransacaos' => $livroTransacaos,
            'erro' => ''
        ), 200);

}

/**
 * recebe o post do AJAX de vender
 *
 * @Route("/vender", name="livrotransacao_vender")
 * @Method({"GET", "POST"})
 *
 */
public function venderAction(Request $request)
{
    //This is optional. Do not do this check if you want to call the same action using a regular request.
    if (!$request->isXmlHttpRequest()) 
    {
        return new JsonResponse(array('message' => 'Somente para uso em Ajax!'), 400);
    }

    try
    {
		if($_POST["qtdCotas"] != '' && $_POST["valorCotas"] != '')
		{
            // venda
            $livroTransacaoVenda = new Livrotransacao();
            $livroTransacaoVenda->setTipoOperacao(1);
            $livroTransacaoVenda->setQuantidadeCotas($_POST["qtdCotas"]);
            $livroTransacaoVenda->setValorCotas($_POST["valorCotas"]);
            $livroTransacaoVenda->setStatus(0);
            //gravar código da conta do usuário
            //$livroTransacaoCompra->setConcor(1);

            $venda = $this->getDoctrine()->getManager();
            $venda->persist($livroTransacaoVenda);
            $venda->flush($livroTransacaoVenda);
        
        }
		else
		{
			return new JsonResponse(array(
				'livroTransacaos' => '',
				'retorno' => '',
				'erro' => 'erro'
			), 200);
		}
        
        //código push

        $options = array(
            'encrypted' => true
        );
        $pusher = new \Pusher(
            '939ce1e989ceb6b401b5',
            '84a028efced54d78f241',
            '301127',
            $options
        );

        //lista transações
        $em = $this->getDoctrine()->getManager();
        $livroTransacaos = $em->getRepository('AppBundle:LivroTransacao')->findAllArray();      
        
        $retorno = new JsonResponse($pusher->trigger('my-channel', 'subscription_succeeded', $livroTransacaos), 200);
        //fim código do push do site

        return new JsonResponse(array(
            'livroTransacaos' => $livroTransacaos,
            'retorno' => $retorno,
            'erro' => ''
        ), 200);

 }catch(Exception $ex){
    return new JsonResponse(array('message' => 'erro'), 200); 
 }
 
    return new JsonResponse(array('message' => 'erro'), 200);
}

    /**
     * Finds and displays a livroTransacao entity.
     *
     * @Route("/{id}", name="livrotransacao_show")
     * @Method("GET")
     */
    public function showAction(LivroTransacao $livroTransacao)
    {
        $deleteForm = $this->createDeleteForm($livroTransacao);

        return $this->render('livrotransacao/show.html.twig', array(
            'livroTransacao' => $livroTransacao,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing livroTransacao entity.
     *
     * @Route("/{id}/edit", name="livrotransacao_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LivroTransacao $livroTransacao)
    {
        $deleteForm = $this->createDeleteForm($livroTransacao);
        $editForm = $this->createForm('AppBundle\Form\LivroTransacaoType', $livroTransacao);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('livrotransacao_edit', array('id' => $livroTransacao->getId()));
        }

        return $this->render('livrotransacao/edit.html.twig', array(
            'livroTransacao' => $livroTransacao,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a livroTransacao entity.
     *
     * @Route("/{id}", name="livrotransacao_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, LivroTransacao $livroTransacao)
    {
        $form = $this->createDeleteForm($livroTransacao);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($livroTransacao);
            $em->flush($livroTransacao);
        }

        return $this->redirectToRoute('livrotransacao_index');
    }

    /**
     * Creates a form to delete a livroTransacao entity.
     *
     * @param LivroTransacao $livroTransacao The livroTransacao entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LivroTransacao $livroTransacao)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('livrotransacao_delete', array('id' => $livroTransacao->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
