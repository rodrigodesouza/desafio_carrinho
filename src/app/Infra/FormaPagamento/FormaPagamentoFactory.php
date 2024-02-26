<?php

namespace Desafio\Carrinho\Infra\FormaPagamento;

use Exception;
use Desafio\Carrinho\Infra\FormaPagamento\ViaPix;
use Desafio\Carrinho\Infra\FormaPagamento\ViaCredito;
use Desafio\Carrinho\Dominio\Entidades\Contracts\FormaPagamento;

final class FormaPagamentoFactory
{
    private array $formasPagamento = [
        'pix' => ViaPix::class,
        'credito' => ViaCredito::class,
    ];

    /**
     * @param string $formaPagamento valor a ser informado por um formulário/botão de seleção.
     * @return \Desafio\Carrinho\Dominio\Entidades\Contracts\FormaPagamento
     */
    public function defineFormaPagamento(string $formaPagamento): FormaPagamento
    {
        if (!array_key_exists($formaPagamento, $this->formasPagamento)) {
            throw new Exception('Forma de pagamento não aceita.');
        }

        return new $this->formasPagamento[$formaPagamento]();
    }
}
