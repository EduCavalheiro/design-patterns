<?php

namespace Alura\DesignPatterns\Impostos;

use Alura\DesignPatterns\Orcamento;

abstract class ImpostoComDuasAliquotas implements Imposto
{
    public function calculaImposto(Orcamento $orcamento): float
    {
        return $this->deveAplicarTaxaMaxima($orcamento)
            ? $this->calculaTaxaMaxima($orcamento)
            : $this->calculaTaxaMinima($orcamento);
    }

    abstract protected function deveAplicarTaxaMaxima(Orcamento $orcamento): bool;
    abstract protected function calculaTaxaMaxima(Orcamento $orcamento): float;
    abstract protected function calculaTaxaMinima(Orcamento $orcamento): float;
}