<?php

namespace core\domain\validation;

use core\domain\exception\EntityValidationException;
use DateTime;

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

    public static function notAfterToday(DateTime $value, string $exceptionMessage = null)
    {
        if ($value) {
            date_default_timezone_set('America/Sao_Paulo');
            $today = new DateTime(today());
    
            if ($value >= $today) {
                throw new EntityValidationException($exceptionMessage ?? "Data de nascimento não pode ser posterior a hoje");
            }
        }
    }

}
