<?php

namespace Orkester\Persistence;

use Orkester\Persistence\PersistenceManager;

class Transaction
{
    public function beginTransaction() {
        PersistenceManager::beginTransaction();
    }

    public function commit() {
        PersistenceManager::commit();
    }

    public function rollback() {
        PersistenceManager::rollback();
    }

}
