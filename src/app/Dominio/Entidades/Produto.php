<?php
declare(strict_types=1);

namespace Desafio\Carrinho\Dominio\Entidades;

use LengthException;
use Desafio\Carrinho\Dominio\Exceptions\NomeProdutoInvalidoException;
use Desafio\Carrinho\Dominio\Exceptions\PrecoProdutoInvalidoException;

final class Produto
{
    readonly public string $uuid;
    readonly public string $nome;
    readonly public float $preco;

    public function __construct(string $uuid, string $nome, float $preco)
    {   
        $this->setUuid($uuid);
        $this->setNome($nome);
        $this->setPreco($preco);
    }

    private function setUuid(string $uuid): void
    {
        if (empty($uuid)) {
            throw new LengthException('Uuid não deve ser vazio');
        }
        $this->uuid = $uuid;
    }

    private function setNome(string $nome): void
    {
        if (empty($nome) || strlen($nome) < 2) {
            throw new NomeProdutoInvalidoException();
        }

        $this->nome = $nome;
    }

    private function setPreco(float $preco): void
    {
        if ((filter_var($preco, FILTER_VALIDATE_FLOAT) && $preco <= 0) || empty($preco)) {
            throw new PrecoProdutoInvalidoException();
        }

        $this->preco = $preco;
    }
}
