<?php

namespace Desafio\Carrinho\Infra\FormaPagamento\Exceptions;

use Exception;

final class MaximoParcelamentoExcedidoException extends Exception
{
    public function __construct()
    {
        parent::__construct('Número do parcelamento ultrapassa o máximo permitido.');
    }
}
