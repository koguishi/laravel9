<?php

namespace core\usecase\interfaces;

interface TransactionInterface
{
    public function commit();

    public function rollback();
}