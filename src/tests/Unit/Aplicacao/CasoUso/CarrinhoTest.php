<?php

namespace Desafio\Tests\Unit\Aplicacao\CasoUso;

use PHPUnit\Framework\TestCase;
use Desafio\Builders\ProdutoBuilder;
use Desafio\Carrinho\Aplicacao\CasoUso\Carrinho;
use Desafio\Carrinho\Dominio\Entidades\Contracts\FormaPagamento;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\FormaPagamentoNaoInformadaException;
use Desafio\Carrinho\Infra\FormaPagamento\FormaPagamentoFactory;

class CarrinhoTest extends TestCase
{
    protected Carrinho $carrinho;

    protected FormaPagamento $formaPagamento;

    public function setUp(): void
    {
        $this->carrinho = new Carrinho();
        $this->formaPagamento = (new FormaPagamentoFactory)->defineFormaPagamento('pix');
    }

    protected function gerarItensAoCarrinho(int $totalItens = 3, $precos = [], $quantidade = [])
    {
        $itens = (new ProdutoBuilder())->geraItensCarrinho($totalItens, $precos, $quantidade);
        $carrinho = $this->carrinho;

        for ($i = 0; $i < count($itens); $i++) {
            $carrinho->adicionaItem($itens[$i]);
        }

        return $carrinho;
    }

    public function testRetornaExceptionFormaPagamentoNaoInformado()
    {
        $this->expectException(FormaPagamentoNaoInformadaException::class);

        $this->gerarItensAoCarrinho();

        $this->carrinho->calculaTotalPedido();
    }

    public function testDeveAdicionarItensAoCarrinhoComQuantidade()
    {
        $itens = (new ProdutoBuilder())->geraItensCarrinho(3);

        $this->carrinho
            ->adicionaItem($itens[0])
            ->adicionaItem($itens[1])
            ->adicionaItem($itens[2]);

        $itensCarrinho = $this->carrinho->getItens();

        $this->assertCount(count($itens), $itensCarrinho);
    }

    public function testDeveRemoverUmItemDoCarrinho()
    {
        $itemIndex = 1;
        $this->gerarItensAoCarrinho(3);

        $this->carrinho->removeItem($itemIndex);

        $itensCarrinho = $this->carrinho->getItens();

        $this->assertFalse(isset($itensCarrinho[$itemIndex]));

        $this->assertCount(2, $itensCarrinho);
    }

    public function testDeveAtualizarUmItemDoCarrinho()
    {
        $itemIndex = 1;
        $novaQuantidade = 5;
        $this->gerarItensAoCarrinho(3, quantidade: [1, 1, 1]);

        $quantidadeItemAnterior = $this->carrinho->getItens()[$itemIndex]->quantidade;
        
        $this->carrinho->atualizarQuantidadeItem(itemIndex: $itemIndex, novaQuantidade: $novaQuantidade);

        $itensCarrinho = $this->carrinho->getItens();

        $this->assertEquals(1, $quantidadeItemAnterior);
        $this->assertEquals($novaQuantidade, $itensCarrinho[$itemIndex]->quantidade);
    }
}
