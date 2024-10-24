<?php

namespace core\domain\validation;

use core\domain\entity\Entity;
use Rakit\Validation\Validator;

class VideoValidationRakit implements ValidationInterface
{
    public function validate(Entity $entity): void
    {

        $data = $this->convertEntityForArray($entity);
        // dd($data);

        $validation = (new Validator())->validate($data, [
            'titulo' => 'required|min:3|max:100',
            'descricao' => 'required|min:3|max:255',
            'dtFilmagem' => 'required|date',
            // 'minutos' => 'required|integer',
        ]);

        if ($validation->fails()) {
            foreach ($validation->errors()->all() as $error) {
                $entity->notification->addError([
                    'context' => 'video',
                    'message' => $error,
                ]);
            }
        }
    }

    private function convertEntityForArray(Entity $entity): array
    {
        return [
            'titulo' => $entity->titulo,
            'descricao' => $entity->descricao,
            'dtFilmagem' => $entity->dtFilmagem->format('Y-d-m'),
            // 'minutos' => $entity->minutos,
        ];
    }
}