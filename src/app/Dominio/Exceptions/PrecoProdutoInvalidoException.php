<?php
namespace Desafio\Carrinho\Dominio\Exceptions;

use InvalidArgumentException;

class PrecoProdutoInvalidoException extends InvalidArgumentException
{
    public function __construct() {
        parent::__construct('Nome do produto deve ter pelo menos 2 caracteres');
    }    
}
