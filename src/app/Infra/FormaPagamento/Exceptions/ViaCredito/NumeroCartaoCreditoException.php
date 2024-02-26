<?php
namespace Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito;

use DomainException;

class NumeroCartaoCreditoException extends DomainException
{
    public function __construct() {
        parent::__construct('Número do cartão de crédito deve ser informado.');
    }
}
