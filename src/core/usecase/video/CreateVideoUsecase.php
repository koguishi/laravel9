<?php

namespace core\usecase\video;

use core\domain\repository\VideoRepositoryInterface;
use core\usecase\interfaces\FileStorageInterface;
use core\usecase\interfaces\TransactionInterface;

class CreateVideoUsecase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $fileStorage,
        protected VideoEventManagerInterface $eventManager,
    ) { }

    public function execute(CreateVideoInput $input): CreateVideoOutput
    {
        // create entity do video usando o $input
        // inserir ids de atleta(s) e categoria(s)
        
        // persitir a entity do video usando $repository

        // armazenar a media usando o id da entity do video para o path usando o $fileStorage
           // disparar o evento usando o $eventManager

        // comitar a transação usando o $transaction
        
        return new CreateVideoOutput(
            titulo: $input->titulo,
            descricao: $input->descricao,
            dtFilmagem: $input->dtFilmagem,
        );
    }
}