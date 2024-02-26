<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Infra\FormaPagamento;

use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\MaximoParcelamentoExcedidoException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\NomeCompradorException;
use DomainException;
use Desafio\Carrinho\Dominio\Entidades\CalculadoraDesconto;
use Desafio\Carrinho\Dominio\Entidades\Contracts\FormaPagamentoComDesconto;

class ViaPix extends FormasPagamentoBase implements FormaPagamentoComDesconto
{
    private ?string $nomeComprador;

    public function __construct()
    {
        $this->setMaximoParcelamento();
    }

    protected function setMaximoParcelamento(): void
    {
        $this->maximoParcelamento = 1;
    }

    /**
     * @param int $parcelamento Informa a quantidade de parcelas para esta compra.
     */
    public function setNumeroParcelamento(int $parcelamento): self
    {
        if ($parcelamento > $this->numeroParcelas) {
            throw new MaximoParcelamentoExcedidoException();
        }
        $this->numeroParcelas = $parcelamento;
        return $this;
    }

    public function setDadosPagamento(array $dadosPagamento): self
    {
        $this->nomeComprador = $dadosPagamento['nome_comprador'] ?? null;

        return $this;
    }

    public function getDadosPagamento(): array
    {
        return [];
    }

    public function getMaximoParcelamento(): int
    {
        return $this->maximoParcelamento;
    }

    public function calculaValorDesconto(float $valorTotalCarrinho): float
    {
        return (new CalculadoraDesconto)->obterValorDoDescontoAVistaDezPorCento($valorTotalCarrinho);
    }

    public function validarDados(): self
    {
        if (empty($this->nomeComprador)) {
            throw new NomeCompradorException();
        }
        return $this;
    }

    public function calculaValorTotalCarrinho(float $valorTotalCarrinho): array
    {
        $valorDesconto = $this->calculaValorDesconto($valorTotalCarrinho);

        return [
            'forma_pagamento' => 'Pix à vista',
            'subtotal' => $valorTotalCarrinho,
            'valor_desconto' => $valorDesconto,
            'valor_total' => $valorTotalCarrinho - $valorDesconto,
        ];
    }
}
