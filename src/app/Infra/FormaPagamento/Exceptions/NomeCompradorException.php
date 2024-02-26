<?php
namespace Desafio\Carrinho\Infra\FormaPagamento\Exceptions;

use DomainException;

final class NomeCompradorException extends DomainException
{
    public function __construct() {
        parent::__construct('Nome do comprador deve ser informado.');
    }
}
