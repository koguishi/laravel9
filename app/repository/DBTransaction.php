<?php

namespace app\repository;

use core\usecase\interfaces\TransactionInterface;
use Illuminate\Support\Facades\DB;

class DBTransaction implements TransactionInterface
{
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollBack();
    }
}