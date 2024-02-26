<?php

declare(strict_types=1);

namespace Desafio\Carrinho\Infra\FormaPagamento;

use Desafio\Carrinho\Dominio\Entidades\Contracts\FormaPagamento;

abstract class FormasPagamentoBase implements FormaPagamento
{
    protected float $valorTotalProdutos;
    /**
     * @var int $maximoParcelamento Quantidade máxima de parcelamento de uma compra nesta forma de pagamento.
     */
    protected int $maximoParcelamento;

    /**
     * @param int $numeroParcelas Quantidade de parcelas para a compra.
     */
    protected int $numeroParcelas = 1;

    abstract public function __construct();

    abstract protected function setMaximoParcelamento(): void;    
}
