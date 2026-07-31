<?php

namespace App\SymfonyTreeEngine\src\Interface;

interface TreeNodeInterface
{
    public function getId(): string;

    public function getLft(): int;

    public function getRgt(): int;

    public function getMetadata(): TreeNodeMetadataInterface;
}
