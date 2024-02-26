<?php

namespace Desafio\Builders;

use Desafio\Carrinho\Dominio\Entidades\Produto;
use Desafio\Carrinho\Dominio\Entidades\ValueObjects\ItemCarrinho;

class ProdutoBuilder
{
    public function geraItensCarrinho(int $totalItens = 3, $precos = [], $quantidade = []): array
    {
        $arrayItens = [];
        $tamanho = count($precos) == 0 ? $totalItens : count($precos);
        for ($i = 0; $i < $tamanho; $i++) {
            $arrayItens[] = new ItemCarrinho(
                produto: new Produto(uuid: "0$i", nome: "Produto $i", preco: $precos[$i] ?? 100.25),
                quantidade: $quantidade[$i] ?? 1,
            );
        }

        return $arrayItens;
    }
}
