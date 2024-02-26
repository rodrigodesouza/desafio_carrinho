<?php

namespace Desafio\Tests\Unit\Dominio\Entidades;

use PHPUnit\Framework\TestCase;
use Desafio\Carrinho\Dominio\Entidades\CalculadoraDesconto;

class CalculadoraDescontoTest extends TestCase
{
    private CalculadoraDesconto $calculadoraDesconto;

    protected function setUp(): void
    {
        $this->calculadoraDesconto = new CalculadoraDesconto();
    }

    public function testValorDoDescontoDeveSerZeroQuandoTotalCompraForZero(): void
    {
        $valorTotalProdutos = 0;
        $valorDesconto = $this->calculadoraDesconto->obterValorDoDescontoAVistaDezPorCento($valorTotalProdutos);

        $this->assertEquals(0, $valorDesconto);
    }

    public function testValorDoDescontoDeveSerZeroQuandoTotalCompraForNegativo(): void
    {
        $valorTotalProdutos = -100;
        $valorDesconto = $this->calculadoraDesconto->obterValorDoDescontoAVistaDezPorCento($valorTotalProdutos);

        $this->assertEquals(0, $valorDesconto);
    }

    public function testValorDoDescontoDeveSerDezPorCentoQuandoTotalCompraContemFracao(): void
    {
        $valorTotalProdutos = 0.50;
        $valorDescontoEsperado = 0.05;
        $valorDesconto = $this->calculadoraDesconto->obterValorDoDescontoAVistaDezPorCento($valorTotalProdutos);

        $this->assertEquals($valorDescontoEsperado, $valorDesconto);
    }

    public function testValorDoDescontoDeveSerDezPorCentoQuandoTotalCompraContemInteiro(): void
    {
        $valorTotalProdutos = 1;
        $valorDescontoEsperado = 0.10;
        $valorDesconto = $this->calculadoraDesconto->obterValorDoDescontoAVistaDezPorCento($valorTotalProdutos);

        $this->assertEquals($valorDescontoEsperado, $valorDesconto);
    }
}
