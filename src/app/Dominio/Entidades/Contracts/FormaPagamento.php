<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Dominio\Entidades\Contracts;

interface FormaPagamento
{    
    public function setDadosPagamento(array $dadosPagamento): self;

    public function getDadosPagamento(): array;

    public function getMaximoParcelamento(): int;

    public function validarDados(): self;

    public function setNumeroParcelamento(int $numeroParcelas): self;

    public function calculaValorTotalCarrinho(float $valorTotalCarrinho): array;
}
