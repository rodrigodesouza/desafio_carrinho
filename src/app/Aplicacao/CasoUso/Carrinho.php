<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Aplicacao\CasoUso;

use Desafio\Carrinho\Dominio\Entidades\Contracts\FormaPagamento;
use Desafio\Carrinho\Dominio\Entidades\ValueObjects\ItemCarrinho;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\FormaPagamentoNaoInformadaException;

class Carrinho
{
    /** @var ItemCarrinho[] */
    private $itens = [];

    private FormaPagamento $formaPagamento;

    private array $dadosPagamento = [];

    private float $valorTotal = 0;

    private int $numeroParcelas = 1;

    public function setNumeroParcelas(int $numeroParcelas): self
    {
        $this->numeroParcelas = $numeroParcelas;

        return $this;
    }

    public function setFormaPagamento(FormaPagamento $formaPagamento): self
    {
        $this->formaPagamento = $formaPagamento;
        return $this;
    }

    public function getFormaPagamento()
    {
        return $this->formaPagamento;
    }

    public function setDadosPagamento($dadosPagamento): self
    {
        $this->dadosPagamento = $dadosPagamento;
        return $this;
    }

    public function getDadosPagamento()
    {
        return $this->dadosPagamento;
    }

    /** @return ItemCarrinho[] */
    public function getItens(): array
    {
        return $this->itens;
    }

    public function getValorTotal(): float
    {
        return $this->valorTotal;
    }

    public function adicionaItem(ItemCarrinho $item): self
    {
        $this->itens[] = $item;
        return $this;
    }

    public function atualizarQuantidadeItem(int $itemIndex,  int $novaQuantidade): self
    {
        $this->itens[$itemIndex]->quantidade = $novaQuantidade;

        return $this;
    }

    public function removeItem(int $itemIndex): self
    {
        unset($this->itens[$itemIndex]);
        return $this;
    }

    public function calculaTotalPedido(): array
    {
        $this->validaFormaPagamento();
        $this->formaPagamento
            ->setDadosPagamento($this->dadosPagamento)
            ->validarDados();

        $this->valorTotal = 0;
        if (count($this->itens) > 0) {
            foreach ($this->itens as $item) {
                $this->valorTotal += ($item->produto->preco * $item->quantidade);
            }
        }

        return $this->formaPagamento
            ->setNumeroParcelamento($this->numeroParcelas)
            ->calculaValorTotalCarrinho($this->valorTotal);
    }

    private function validaFormaPagamento(): void
    {
        if (empty($this->formaPagamento)) {
            throw new FormaPagamentoNaoInformadaException();
        }
    }
}
