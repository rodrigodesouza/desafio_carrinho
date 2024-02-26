<?php
namespace Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito;

use DomainException;

class CodigoSegurancaCartaoException extends DomainException
{
    public function __construct() {
        parent::__construct('Código de segurança (CVV) deve ser informado.');
    }
}
