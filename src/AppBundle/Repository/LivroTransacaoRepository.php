<?php

namespace AppBundle\Repository;

/**
 * LivroTransacaoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LivroTransacaoRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllArray()
    {
        /*$em = $this->getDoctrine()->getManager();
        $livroTransacaos = $em->getRepository('AppBundle:LivroTransacao')->findAll();
        return $livroTransacaos;*/

        $sql = "SELECT * FROM livro_transacao ";
                $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll();
    }

}