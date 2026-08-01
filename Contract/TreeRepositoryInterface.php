<?php

namespace App\SymfonyTreeEngine\Contract;
interface TreeRepositoryInterface
{
    public function load(object $Root): void;

    public function save(object $Root): void;

    public function delete(object $Root): void;

    public function transaction(object $Root): void;

    public function unlock(object $Root): void;

    public function flush(object $Root): void;

}
