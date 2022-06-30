<?php

namespace Alura\DesignPatterns\Descontos;

use Alura\DesignPatterns\Orcamento;

class DescontoMaisDeQuinhentosReais extends Desconto
{
    public function calculaDesconto(Orcamento $orcamento): float
    {
        return $orcamento->valor >= 100
            ? $orcamento->valor * 0.05
            : $this->proximoDesconto->calculaDesconto($orcamento);
    }
}