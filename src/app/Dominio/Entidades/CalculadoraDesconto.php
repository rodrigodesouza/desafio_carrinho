<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Dominio\Entidades;

class CalculadoraDesconto
{
    public function obterValorDoDescontoAVistaDezPorCento(float $valorTotalCarrinho): float
    {
        if ($valorTotalCarrinho <= 0) {
            return 0;
        }

        $poncentagemDescontoAvista = 0.10;

        return round($poncentagemDescontoAvista * $valorTotalCarrinho, 2);
    }
}
