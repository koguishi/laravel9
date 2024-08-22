<?php

namespace core\domain\validation;

use core\domain\exception\EntityValidationException;

class DomainValidation
{
    public static function notNull(string $value, string $exceptionMessage = null)
    {
        if (empty($value)) {
            throw new EntityValidationException($exceptionMessage ?? 'Não pode ser nulo ou vazio');
        }
    }

    public static function strMaxLen(string $value, int $max = 255, string $exceptionMessage = null)
    {
        if (strlen($value) > $max) {
            throw new EntityValidationException($exceptionMessage ?? 'Não pode ser maior que {$max}');
        }
    }

    public static function strMinLen(string $value, int $min = 3, string $exceptionMessage = null)
    {
        if (strlen($value) < $min) {
            throw new EntityValidationException($exceptionMessage ?? 'Não pode ser menor que {$min}');
        }
    }

    public static function strCanNullButMaxLen(string $value = null, int $max = 255, string $exceptionMessage = null)
    {
        if (!empty($value) && strlen($value) > $max) {
            throw new EntityValidationException($exceptionMessage ?? 'Não pode ser maior que {$max}');
        }
    }
}
