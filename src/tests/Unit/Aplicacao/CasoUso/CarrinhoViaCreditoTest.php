<?php

namespace Desafio\Tests\Unit\Aplicacao\CasoUso;

use Desafio\Carrinho\Aplicacao\CasoUso\Carrinho;
use Desafio\Carrinho\Infra\FormaPagamento\FormaPagamentoFactory;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\MaximoParcelamentoExcedidoException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\NomeTitularCartaoException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\DataValidadeCartaoException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\FormatoDataValidadeException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\NumeroCartaoCreditoException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\CodigoSegurancaCartaoException;

class CarrinhoViaCreditoTest extends CarrinhoTest
{
    private array $dadosCartao = [];
    public function setUp(): void
    {
        $this->carrinho = new Carrinho();
        $this->formaPagamento = (new FormaPagamentoFactory)->defineFormaPagamento('credito');
        $this->dadosCartao = [
            'nome_titular_cartao' => 'John Doe',
            'numero_cartao' => '1234567890',
            'data_validade_cartao' => '2029-12-31',
            'codigo_seguranca_cvv_cartao' => 123,
        ];
    }

    public function testRetornaExceptionNomeTitularCartaoCreditoNaoInformado()
    {
        $this->expectException(NomeTitularCartaoException::class);

        $dadosPagamento = $this->dadosCartao;
        $dadosPagamento['nome_titular_cartao'] = '';

        $this->gerarItensAoCarrinho();

        $this->carrinho->setNumeroParcelas(1)
            ->setDadosPagamento($dadosPagamento)
            ->setFormaPagamento($this->formaPagamento);

        $this->carrinho->calculaTotalPedido();
    }

    public function testRetornaExceptionNumeroCartaoCreditoNaoInformado()
    {
        $this->expectException(NumeroCartaoCreditoException::class);

        $dadosPagamento = $this->dadosCartao;
        $dadosPagamento['numero_cartao'] = '';

        $this->gerarItensAoCarrinho();

        $this->carrinho->setNumeroParcelas(1)
            ->setDadosPagamento($dadosPagamento)
            ->setFormaPagamento($this->formaPagamento);

        $this->carrinho->calculaTotalPedido();
    }

    public function testRetornaExceptionDataValidadeCartaoCreditoNaoInformado()
    {
        $this->expectException(DataValidadeCartaoException::class);

        $dadosPagamento = $this->dadosCartao;
        $dadosPagamento['data_validade_cartao'] = '';

        $this->gerarItensAoCarrinho();

        $this->carrinho->setNumeroParcelas(1)
            ->setDadosPagamento($dadosPagamento)
            ->setFormaPagamento($this->formaPagamento);

        $this->carrinho->calculaTotalPedido();
    }

    public function testRetornaExceptionDataValidadeCartaoCreditoFormatoInvalido()
    {
        $this->expectException(FormatoDataValidadeException::class);

        $dadosPagamento = $this->dadosCartao;
        $dadosPagamento['data_validade_cartao'] = '01/01/2023';

        $this->gerarItensAoCarrinho();

        $this->carrinho->setNumeroParcelas(1)
            ->setDadosPagamento($dadosPagamento)
            ->setFormaPagamento($this->formaPagamento);

        $this->carrinho->calculaTotalPedido();
    }

    public function testRetornaExceptionCodigoSegurancaCvvCartaoCreditoNaoInformado()
    {
        $this->expectException(CodigoSegurancaCartaoException::class);

        $dadosPagamento = $this->dadosCartao;
        $dadosPagamento['codigo_seguranca_cvv_cartao'] = null;

        $this->gerarItensAoCarrinho();

        $this->carrinho->setNumeroParcelas(1)
            ->setDadosPagamento($dadosPagamento)
            ->setFormaPagamento($this->formaPagamento);

        $this->carrinho->calculaTotalPedido();
    }

    public function testRetornaExceptionAoExcederLimiteParcelamentoPix()
    {
        $this->expectException(MaximoParcelamentoExcedidoException::class);

        $dadosPagamento = $this->dadosCartao;

        $this->gerarItensAoCarrinho();

        $this->carrinho->setNumeroParcelas(13)
            ->setDadosPagamento($dadosPagamento)
            ->setFormaPagamento($this->formaPagamento);

        $this->carrinho->calculaTotalPedido();
    }

    public function testDeveRetornarTotalCarrinhoComDescontoAVistaCartaoCredito()
    {

        $this->gerarItensAoCarrinho(precos: [20.50, 30.50, 49, 100,], quantidade: [1, 1, 1, 2]);

        $this->carrinho->setNumeroParcelas(1)
            ->setDadosPagamento($this->dadosCartao)
            ->setFormaPagamento($this->formaPagamento);

        $calculoTotalPedido = $this->carrinho->calculaTotalPedido();

        $this->assertArrayHasKey('subtotal', $calculoTotalPedido);
        $this->assertArrayHasKey('valor_desconto', $calculoTotalPedido);
        $this->assertArrayHasKey('valor_total', $calculoTotalPedido);

        $this->assertEquals(300, $calculoTotalPedido['subtotal']);
        $this->assertEquals(30.00, $calculoTotalPedido['valor_desconto']);
        $this->assertEquals(270.00, $calculoTotalPedido['valor_total']);
    }

    public function testDeveRetornarTotalCarrinhoParcelado12xCartaoCredito()
    {

        $this->gerarItensAoCarrinho(precos: [20.50, 30.50, 49, 100,], quantidade: [1, 1, 1, 2]);

        $this->carrinho->setNumeroParcelas(12)
            ->setDadosPagamento($this->dadosCartao)
            ->setFormaPagamento($this->formaPagamento);

        $calculoTotalPedido = $this->carrinho->calculaTotalPedido();

        $this->assertArrayHasKey('subtotal', $calculoTotalPedido);
        $this->assertArrayHasKey('valor_desconto', $calculoTotalPedido);
        $this->assertArrayHasKey('valor_total', $calculoTotalPedido);
        $this->assertArrayHasKey('numero_parcelas', $calculoTotalPedido);

        $this->assertEquals(300, $calculoTotalPedido['subtotal']);
        $this->assertEquals(0.00, $calculoTotalPedido['valor_desconto']);
        $this->assertEquals(338.05, $calculoTotalPedido['valor_total']);
    }

    public function testDeveRetornarTotalCarrinhoParcelado2xCartaoCredito()
    {

        $this->gerarItensAoCarrinho(precos: [20.50, 30.50, 49, 100,], quantidade: [1, 1, 1, 2]);

        $this->carrinho->setNumeroParcelas(2)
            ->setDadosPagamento($this->dadosCartao)
            ->setFormaPagamento($this->formaPagamento);

        $calculoTotalPedido = $this->carrinho->calculaTotalPedido();

        $this->assertArrayHasKey('subtotal', $calculoTotalPedido);
        $this->assertArrayHasKey('valor_desconto', $calculoTotalPedido);
        $this->assertArrayHasKey('valor_total', $calculoTotalPedido);
        $this->assertArrayHasKey('numero_parcelas', $calculoTotalPedido);

        $this->assertEquals(300, $calculoTotalPedido['subtotal']);
        $this->assertEquals(0.00, $calculoTotalPedido['valor_desconto']);
        $this->assertEquals(306.03, $calculoTotalPedido['valor_total']);
    }
}
