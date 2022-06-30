<?php

namespace Alura\DesignPatterns;

use Alura\DesignPatterns\Descontos\DescontoMaisDeCincoItens;
use Alura\DesignPatterns\Descontos\DescontoMaisDeQuinhentosReais;
use Alura\DesignPatterns\Descontos\SemDesconto;

class CalculadoraDeDescontos
{
    public function calculaDescontos(Orcamento $orcamento): float
    {
        $cadeiaDescontos = new DescontoMaisDeCincoItens(
            new DescontoMaisDeQuinhentosReais(
                new SemDesconto()
            )
        );

        return $cadeiaDescontos->calculaDesconto($orcamento);
    }
}