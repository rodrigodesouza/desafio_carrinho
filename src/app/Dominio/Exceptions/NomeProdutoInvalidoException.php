<?php
namespace Desafio\Carrinho\Dominio\Exceptions;

use LengthException;

class NomeProdutoInvalidoException extends LengthException
{
    public function __construct() {
        parent::__construct('Nome do produto deve ter pelo menos 2 caracteres');
    }
}
