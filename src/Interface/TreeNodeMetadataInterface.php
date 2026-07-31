<?php

namespace App\SymfonyTreeEngine\src\Interface;

interface TreeNodeMetadataInterface
{
    public function getName(): ?string;

    public function getDescription(): ?string;
}
