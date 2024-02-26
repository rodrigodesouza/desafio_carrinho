<?php

namespace Desafio\Tests\Unit\Dominio\Entidades;

use LengthException;
use PHPUnit\Framework\TestCase;
use Desafio\Carrinho\Dominio\Entidades\Produto;
use Desafio\Carrinho\Dominio\Exceptions\NomeProdutoInvalidoException;
use Desafio\Carrinho\Dominio\Exceptions\PrecoProdutoInvalidoException;

class ProdutoTest extends TestCase
{
    private string $uuid;
    private string $nomeProduto;
    private float $precoProduto;

    protected function setUp(): void
    {
        $this->uuid = uniqid();
        $this->nomeProduto = 'Nome Produto';
        $this->precoProduto = 100.10;
    }
    public function testRetornaExceptionQuandoEnviaUuidProdutoVazio(): void
    {
        $this->expectException(LengthException::class);
        new Produto(uuid: '', nome: $this->nomeProduto, preco: $this->precoProduto);
    }

    public function testRetornaExceptionQuandoEnviaNomeProdutoVazio(): void
    {
        $this->expectException(LengthException::class);
        new Produto(uuid: $this->uuid, nome: '', preco: $this->precoProduto);
    }

    public function testRetornaExceptionQuandoNomeProdutoForMenorQueDoisCaracteres(): void
    {
        $this->expectException(NomeProdutoInvalidoException::class);
        new Produto(uuid: $this->uuid, nome: substr($this->nomeProduto, 0, 1), preco: $this->precoProduto);
    }

    public function testRetornaExceptionQuandoEnviaPrecoProdutoNegativo(): void
    {
        $this->expectException(PrecoProdutoInvalidoException::class);
        new Produto(uuid: $this->uuid, nome: $this->nomeProduto, preco: -10.00);
    }

    public function testRetornaExceptionQuandoEnviaPrecoProdutoZero(): void
    {
        $this->expectException(PrecoProdutoInvalidoException::class);
        new Produto(uuid: $this->uuid, nome: $this->nomeProduto, preco: '0');
    }

    public function testDeveRetornarProdutoComTodosCampos(): void
    {
        $produto = new Produto(uuid: $this->uuid, nome: $this->nomeProduto, preco: $this->precoProduto);

        $this->assertInstanceOf(Produto::class, $produto);
        $this->assertEquals($this->nomeProduto, $produto->nome);
        $this->assertEquals($this->precoProduto, $produto->preco);
        $this->assertEquals($this->uuid, $produto->uuid);
    }
}
