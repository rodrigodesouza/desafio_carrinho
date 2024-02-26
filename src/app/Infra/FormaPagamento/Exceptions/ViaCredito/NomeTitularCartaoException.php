<?php
namespace Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito;

use DomainException;

final class NomeTitularCartaoException extends DomainException
{
    public function __construct() {
        parent::__construct('Nome do titular do cartão deve ser informado.');
    }
}
