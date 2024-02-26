<?php

namespace Desafio\Tests\Unit\Dominio\Entidades;

use PHPUnit\Framework\TestCase;
use Desafio\Carrinho\Dominio\Entidades\CalculadoraJuros;

class CalculadoraJurosTest extends TestCase
{
    private CalculadoraJuros $calculadoraJuros;

    public function setUp(): void
    {
        $this->calculadoraJuros = new CalculadoraJuros();
    }

    public function testAsseguraValorComParcelamentoCreditoZero()
    {
        $valorTotalProdutos = 100;
        $numeroParcelas = 0;
        $valoresJuros = $this->calculadoraJuros->retornaValoresParcelamentoComJurosUmPorCento(
            $valorTotalProdutos,
            $numeroParcelas
        );

        $this->assertEquals($valorTotalProdutos, $valoresJuros['valor_total_com_juros']);
        $this->assertEquals($numeroParcelas, $valoresJuros['numero_parcelas']);
    }

    public function testAsseguraValorComParcelamentoCreditoAVista()
    {
        $valorTotalProdutos = 100;
        $numeroParcelas = 1;
        $valoresJuros = $this->calculadoraJuros->retornaValoresParcelamentoComJurosUmPorCento(
            $valorTotalProdutos,
            $numeroParcelas
        );

        $this->assertEquals($valorTotalProdutos, $valoresJuros['valor_total_com_juros']);
        $this->assertEquals($numeroParcelas, $valoresJuros['numero_parcelas']);
    }

    public function testDeveRetornarDadosDoCalculoJurosComParcelasCorretamente()
    {
        $valorTotalProdutos = 100;
        $numeroParcelas = 3;
        $valorJurosEsperado = 103.03;
        $valoresJuros = $this->calculadoraJuros->retornaValoresParcelamentoComJurosUmPorCento(
            $valorTotalProdutos,
            $numeroParcelas
        );

        $this->assertArrayHasKey('taxa_juros_ao_mes', $valoresJuros);
        $this->assertArrayHasKey('valor_total_com_juros', $valoresJuros);
        $this->assertArrayHasKey('numero_parcelas', $valoresJuros);
        $this->assertEquals($valorJurosEsperado, $valoresJuros['valor_total_com_juros']);
        $this->assertEquals($numeroParcelas, $valoresJuros['numero_parcelas']);
    }
}
