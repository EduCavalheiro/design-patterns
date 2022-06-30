<?php

namespace Alura\DesignPatterns\Descontos;

use Alura\DesignPatterns\Orcamento;

class DescontoMaisDeCincoItens extends Desconto
{
    public function calculaDesconto(Orcamento $orcamento): float
    {
        return $orcamento->quantidadeItens > 5
            ? $orcamento->valor * 0.1
            : $this->proximoDesconto->calculaDesconto($orcamento);
    }
}