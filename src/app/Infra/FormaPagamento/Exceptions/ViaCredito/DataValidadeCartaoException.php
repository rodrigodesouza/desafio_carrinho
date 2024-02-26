<?php
namespace Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito;

use DomainException;

class DataValidadeCartaoException extends DomainException
{
    public function __construct() {
        parent::__construct('Data de validade do cartão deve ser informada.');
    }
}
