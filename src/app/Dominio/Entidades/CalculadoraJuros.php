<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Dominio\Entidades;

class CalculadoraJuros
{
    public function retornaValoresParcelamentoComJurosUmPorCento(
        float $valorTotalCarrinho,
        int $numeroParcelas
    ): array {
        $taxaJurosAoMes = 1;
        $montante = $valorTotalCarrinho;

        if ($numeroParcelas > 1) {
            $montante = $valorTotalCarrinho * pow(1 + ($taxaJurosAoMes / 100), $numeroParcelas);
        }

        return [
            'taxa_juros_ao_mes' => $taxaJurosAoMes,
            'numero_parcelas' => $numeroParcelas,
            'valor_total_com_juros' => round($montante, 2),
        ];
    }
}
