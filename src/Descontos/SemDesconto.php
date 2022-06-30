<?php

namespace Alura\DesignPatterns\Descontos;

use Alura\DesignPatterns\Orcamento;

class SemDesconto extends Desconto
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public function calculaDesconto(Orcamento $orcamento): float
    {
        return 0;
    }
}