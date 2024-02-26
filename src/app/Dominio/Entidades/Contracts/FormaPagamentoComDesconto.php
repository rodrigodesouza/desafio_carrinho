<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Dominio\Entidades\Contracts;

interface FormaPagamentoComDesconto
{
    public function calculaValorDesconto(float $valorTotalProdutos);
}
