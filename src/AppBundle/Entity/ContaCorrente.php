<?php

namespace AppBundle\Entity;

/**
 * ContaCorrente
 */
class ContaCorrente
{
    /**
     * @var string
     */
    private $saldo;
 
    /**
     * @var integer
     */
    private $cotasDisponiveis;

    /**
     * @var integer
     */
    private $concorId;


    /**
     * Set saldo
     *
     * @param string $saldo
     *
     * @return ContaCorrente
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return string
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set cotasDisponiveis
     *
     * @param integer $cotasDisponiveis
     *
     * @return ContaCorrente
     */
    public function setCotasDisponiveis($cotasDisponiveis)
    {
        $this->cotasDisponiveis = $cotasDisponiveis;

        return $this;
    }

    /**
     * Get cotasDisponiveis
     *
     * @return integer
     */
    public function getCotasDisponiveis()
    {
        return $this->cotasDisponiveis;
    }

    /**
     * Get concorId
     *
     * @return integer
     */
    public function getConcorId()
    {
        return $this->concorId;
    }
}
