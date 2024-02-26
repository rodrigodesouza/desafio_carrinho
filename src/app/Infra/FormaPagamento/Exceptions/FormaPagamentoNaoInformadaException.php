<?php

namespace Desafio\Carrinho\Infra\FormaPagamento\Exceptions;

use DomainException;

final class FormaPagamentoNaoInformadaException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Forma de pagamento deve ser informada.', 0);
    }
}
