<?php

namespace core\domain\validation;

use core\domain\entity\Entity;
use DateTime;

class VideoValidationManual implements ValidationInterface
{
    public function validate(Entity $entity): void
    {

        $tituloMinLen = 3;
        $tituloMaxLen = 100;

        if (strlen($entity->titulo) < $tituloMinLen) {
            $entity->notification->addError([
                'context' => 'video',
                'message' => "Titulo deve ter no mínimo {$tituloMinLen} caracteres",
            ]);
        }

        if (strlen($entity->titulo) > $tituloMaxLen) {
            $entity->notification->addError([
                'context' => 'video',
                'message' => "Titulo deve ter no máximo {$tituloMaxLen} caracteres",
            ]);
        }

        if ($entity->dtFilmagem) {
            date_default_timezone_set('America/Sao_Paulo');

            $dtLimit = new DateTime(today());
            if ($entity->dtFilmagem > $dtLimit) {
                $entity->notification->addError([
                    'context' => 'video',
                    'message' => 'Data de filmagem não pode ser posterior a hoje',
                ]);
            }

            $dtLimit->modify('-30 years');
            if ($entity->dtFilmagem <= $dtLimit) {
                $entity->notification->addError([
                    'context' => 'video',
                    'message' => 'Data de filmagem não pode ser anterior a 30 anos',
                ]);
            }
        }
    }
}
