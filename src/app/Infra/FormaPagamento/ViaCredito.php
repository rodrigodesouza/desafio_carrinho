<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Infra\FormaPagamento;

use Desafio\Carrinho\Dominio\Entidades\CalculadoraJuros;
use Desafio\Carrinho\Dominio\Entidades\CalculadoraDesconto;
use Desafio\Carrinho\Dominio\Entidades\Contracts\FormaPagamentoComJuros;
use Desafio\Carrinho\Infra\FormaPagamento\ValueObjects\DataValidadeCartao;
use Desafio\Carrinho\Dominio\Entidades\Contracts\FormaPagamentoComDesconto;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\MaximoParcelamentoExcedidoException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\NomeTitularCartaoException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\DataValidadeCartaoException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\NumeroCartaoCreditoException;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\CodigoSegurancaCartaoException;

class ViaCredito extends FormasPagamentoBase implements FormaPagamentoComJuros, FormaPagamentoComDesconto
{
    private string $nomeComprador;

    private string $numeroCartaoCredito;

    private DataValidadeCartao $dataValidadeCartao;

    private int $codigoSegurancaCvv;

    public function __construct()
    {
        $this->setMaximoParcelamento();
    }

    protected function setMaximoParcelamento(): void
    {
        $this->maximoParcelamento = 12;
    }

    public function setDadosPagamento(array $dadosPagamento): self
    {
        $this->nomeComprador = $dadosPagamento['nome_titular_cartao'] ?? null;
        $this->numeroCartaoCredito = $dadosPagamento['numero_cartao'] ?? null;

        if (! empty($dadosPagamento['data_validade_cartao'])) {
            $this->dataValidadeCartao = new DataValidadeCartao($dadosPagamento['data_validade_cartao']);
        }

        if (! empty($dadosPagamento['codigo_seguranca_cvv_cartao'])) {
            $this->codigoSegurancaCvv = $dadosPagamento['codigo_seguranca_cvv_cartao'];
        }

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

    public function calculaValorJuros(float $valorTotalProdutos, int $numeroParcelas): array
    {
        return (new CalculadoraJuros)->retornaValoresParcelamentoComJurosUmPorCento($valorTotalProdutos, $numeroParcelas);
    }

    public function calculaValorDesconto(float $valorTotalProdutos): float
    {
        if ($this->numeroParcelas > 1) {
            return 0;
        }
        return (new CalculadoraDesconto)->obterValorDoDescontoAVistaDezPorCento($valorTotalProdutos);
    }

    public function validarDados(): self
    {
        if (empty($this->nomeComprador)) {
            throw new NomeTitularCartaoException();
        }

        if (empty($this->numeroCartaoCredito)) {
            throw new NumeroCartaoCreditoException();
        }

        if (empty($this->dataValidadeCartao)) {
            throw new DataValidadeCartaoException();
        }

        if (empty($this->codigoSegurancaCvv)) {
            throw new CodigoSegurancaCartaoException();
        }

        return $this;
    }

    /**
     * @param int $parcelamento Informa a quantidade de parcelas para esta compra.
     */
    public function setNumeroParcelamento(int $parcelamento): self
    {
        if ($parcelamento > $this->maximoParcelamento) {
            throw new MaximoParcelamentoExcedidoException();
        }
        $this->numeroParcelas = $parcelamento;
        return $this;
    }

    public function calculaValorTotalCarrinho(float $valorTotalCarrinho): array
    {
        $valorDesconto = $this->calculaValorDesconto($valorTotalCarrinho);

        $calculoJuros = $this->calculaValorJuros($valorTotalCarrinho, $this->numeroParcelas);
        $valorJuros = $calculoJuros['valor_total_com_juros'] - $valorTotalCarrinho;

        return [
            'forma_pagamento' => 'C. Crédito ' . $this->numeroParcelas . 'x',
            'numero_parcelas' => $calculoJuros['numero_parcelas'],
            'subtotal' => $valorTotalCarrinho,
            'valor_desconto' => $valorDesconto,
            'valor_total' => ($valorTotalCarrinho - $valorDesconto) + $valorJuros,
            'taxa_juros_ao_mes' => $calculoJuros['taxa_juros_ao_mes'],
        ];
    }
}
