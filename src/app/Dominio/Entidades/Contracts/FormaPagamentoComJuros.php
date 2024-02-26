<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Dominio\Entidades\Contracts;

interface FormaPagamentoComJuros
{
    public function calculaValorJuros(float $valorTotalProdutos, int $numeroParcelas): array;
}
