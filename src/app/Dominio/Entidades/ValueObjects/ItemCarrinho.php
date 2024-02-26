<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Dominio\Entidades\ValueObjects;

use Desafio\Carrinho\Dominio\Entidades\Produto;

final class ItemCarrinho
{
    public Produto $produto;

    public int $quantidade;

    public function __construct(Produto $produto, int $quantidade)
    {
        $this->produto = $produto;
        $this->quantidade = $quantidade;
    }

    public function alteraQuantidade(int $quantidade): void
    {
        $this->quantidade = $quantidade;
    }
}
