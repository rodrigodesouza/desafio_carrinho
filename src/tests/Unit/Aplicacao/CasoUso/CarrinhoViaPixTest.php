<?php

namespace Desafio\Tests\Unit\Aplicacao\CasoUso;

use Desafio\Carrinho\Aplicacao\CasoUso\Carrinho;
use Desafio\Carrinho\Infra\FormaPagamento\FormaPagamentoFactory;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\NomeCompradorException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\MaximoParcelamentoExcedidoException;

class CarrinhoViaPixTest extends CarrinhoTest
{
    private array $dadosComprador = [];
    public function setUp(): void
    {
        $this->carrinho = new Carrinho();
        $this->formaPagamento = (new FormaPagamentoFactory)->defineFormaPagamento('pix');
        $this->dadosComprador = [
            'nome_comprador' => 'John Doe',
        ];
    }

    public function testRetornaExceptionNomeCompradorNaoInformado()
    {
        $this->expectException(NomeCompradorException::class);

        $this->gerarItensAoCarrinho();
        $this->carrinho->setFormaPagamento($this->formaPagamento);
        $this->carrinho->calculaTotalPedido();
    }

    public function testRetornaExceptionAoExcederLimiteParcelamentoPix()
    {
        $this->expectException(MaximoParcelamentoExcedidoException::class);

        $this->gerarItensAoCarrinho();

        $this->carrinho->setNumeroParcelas(2)
            ->setDadosPagamento($this->dadosComprador)
            ->setFormaPagamento($this->formaPagamento);

        $this->carrinho->calculaTotalPedido();
    }

    public function testDeveRetornarTotalCarrinhoComDesconto()
    {

        $this->gerarItensAoCarrinho(precos: [20.50, 30.50, 49, 100,], quantidade: [1, 1, 1, 2]);

        $this->carrinho->setNumeroParcelas(1)
            ->setDadosPagamento($this->dadosComprador)
            ->setFormaPagamento($this->formaPagamento);

        $calculoTotalPedido = $this->carrinho->calculaTotalPedido();

        $this->assertArrayHasKey('subtotal', $calculoTotalPedido);
        $this->assertArrayHasKey('valor_desconto', $calculoTotalPedido);
        $this->assertArrayHasKey('valor_total', $calculoTotalPedido);

        $this->assertEquals(300, $calculoTotalPedido['subtotal']);
        $this->assertEquals(30.00, $calculoTotalPedido['valor_desconto']);
        $this->assertEquals(270.00, $calculoTotalPedido['valor_total']);
    }
}
