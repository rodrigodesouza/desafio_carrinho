<?php

namespace Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito;

use Exception;

final class FormatoDataValidadeException extends Exception
{
    public function __construct()
    {
        parent::__construct('Data de validade deve ser informada no formato yyyy-mm-dd.');
    }
}
