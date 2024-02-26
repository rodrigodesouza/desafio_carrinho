<?php

namespace Desafio\Carrinho\Infra\FormaPagamento\ValueObjects;

use DateTime;
use DateTimeInterface;
use Desafio\Carrinho\Infra\FormaPagamento\Exceptions\ViaCredito\FormatoDataValidadeException;

class DataValidadeCartao
{
    private DateTimeInterface $dataValidadeCartao;

    public function __construct($dataValidadeCartao)
    {
        if ($this->checarFormatoData($dataValidadeCartao)) {
            $this->dataValidadeCartao = new DateTime($dataValidadeCartao);
        }
    }

    private function checarFormatoData($dataValidadeCartao): void
    {
        if (empty($dataValidadeCartao) || ! $this->validateDate($dataValidadeCartao)) {
            throw new FormatoDataValidadeException;
        }
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function getDataValidadeCartao(): DateTimeInterface
    {
        return $this->dataValidadeCartao;
    }
}
