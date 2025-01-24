<?php

namespace core\domain\entity;

use core\domain\exception\EntityValidationException;
use core\domain\factory\VideoValidationFactory;
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

    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }

    public function setVideoFile(Media $videoFile): void
    {
        $this->videoFile = $videoFile;
    }

    private function validate()
    {
        VideoValidationFactory::create()->validate($this);

        if ($this->notification->hasError()) {
            // dúvida: deveria ser NotificationException() ?
            throw new EntityValidationException(
                $this->notification->getMessage('video')
            );
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

    public function desvincularCategorias()
    {
        $this->categoriaIds = [];
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

    public function desvincularAtletas()
    {
        $this->atletaIds = [];
    }
}
