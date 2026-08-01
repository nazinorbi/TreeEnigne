<?php

namespace App\SymfonyTreeEngine\Contract;
interface TreeManagerInterface
{
    public function createRoot(object $Root): void;

    public function insert(object $object): void;

    public function move(object $object): void;

    public function delete(object $object): void;

    public function merge(object $object): void;

    public function split(object $object): void;
    public function rebuild(object $object): void;

    public function verify(object $object): void;

    public function export(object $object): void;

    public function import(object $object): void;
}
