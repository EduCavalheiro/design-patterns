<?php

use Alura\DesignPatterns\CalculadoraDeDescontos;
use Alura\DesignPatterns\CalculadoraDeImpostos;
use Alura\DesignPatterns\Impostos\Icms;
use Alura\DesignPatterns\Impostos\Iss;
use Alura\DesignPatterns\Orcamento;

require 'vendor/autoload.php';

//$calculadora = new CalculadoraDeImpostos();
//
//$orcamento = new Orcamento();
//$orcamento->valor = 100;
//
//echo $calculadora->calcula($orcamento, new Iss()) . PHP_EOL;

$calculadora = new CalculadoraDeDescontos();

$orcamento = new Orcamento();
$orcamento->valor = 100; //5%
$orcamento->quantidadeItens = 6; //10%

echo $calculadora->calculaDescontos($orcamento) . PHP_EOL;