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
}