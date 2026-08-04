<?php

namespace App\PhyloTree\Exception;

use RuntimeException;
use Throwable;
/**
 * Exception thrown when a storage operation fails.
 */
class TreeStorageException extends RuntimeException
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
