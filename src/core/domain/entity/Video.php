<?php

namespace core\domain\entity;

use core\domain\exception\EntityValidationException;
use core\domain\valueobject\Media;
use core\domain\valueobject\Uuid;
use DateTime;

class Video extends Entity
{
    protected array $categoriaIds = [];
    protected array $atletaIds = [];

    public function __construct(
        protected string $titulo,
        protected string $descricao,
        protected DateTime $dtFilmagem,
        protected ?Uuid $id = null,
        protected ?DateTime $criadoEm = null,
        protected ?Media $videoFile = null,
    ) {
        parent::__construct();
        $this->id = $this->id ?? Uuid::random();
        $this->criadoEm = $this->criadoEm ?? new DateTime();
        $this->validate();
    }

    public function dtFilmagem(): string
    {
        return $this->dtFilmagem->format('Y-m-d');
    }    

    private function validate()
    {
        $tituloMinLen = 3;
        $tituloMaxLen = 100;

        if (strlen($this->titulo) < $tituloMinLen) {
            $this->notification->addError([
                'context' => 'video',
                'message' => "Titulo deve ter no mínimo {$tituloMinLen} caracteres",
            ]);
        }

        if (strlen($this->titulo) > $tituloMaxLen) {
            $this->notification->addError([
                'context' => 'video',
                'message' => "Titulo deve ter no máximo {$tituloMaxLen} caracteres",
            ]);
        }

        if ($this->dtFilmagem) {
            date_default_timezone_set('America/Sao_Paulo');

            $dtLimit = new DateTime(today());
            if ($this->dtFilmagem > $dtLimit) {
                $this->notification->addError([
                    'context' => 'video',
                    'message' => 'Data de filmagem não pode ser posterior a hoje',
                ]);
            }

            $dtLimit->modify('-30 years');
            if ($this->dtFilmagem <= $dtLimit) {
                $this->notification->addError([
                    'context' => 'video',
                    'message' => 'Data de filmagem não pode ser anterior a 30 anos',
                ]);
            }
        }

        if ($this->notification->hasError()) {
            throw new EntityValidationException($this->notification->getMessage());
        }
    }

    public function alterar(
        ?string $titulo = null,
        ?string $descricao = null,
        ?DateTime $dtFilmagem = null,
    )
    {
        $this->titulo = $titulo ?? $this->titulo;
        $this->descricao = $descricao ?? $this->descricao;
        $this->dtFilmagem = $dtFilmagem ?? $this->dtFilmagem;
        $this->validate();
    }

    public function vincularCategoria(string $categoriaId)
    {
        // throw new EntityValidationException('Categoria {$categoriaId} já está vinculada');
        if (!in_array($categoriaId, $this->categoriaIds)) {
            array_push($this->categoriaIds, $categoriaId);
        }
    }

    public function desvincularCategoria(string $categoriaId)
    {
        $index = array_search($categoriaId, $this->categoriaIds);
        unset($this->categoriaIds[$index]);
    }

    public function vincularAtleta(string $atletaId)
    {
        // throw new EntityValidationException('Atleta {$atletaId} já está vinculado');
        if (!in_array($atletaId, $this->atletaIds)) {
            array_push($this->atletaIds, $atletaId);
        }
    }

    public function desvincularAtleta(string $atletaId)
    {
        $index = array_search($atletaId, $this->atletaIds);
        unset($this->atletaIds[$index]);
    }
}
