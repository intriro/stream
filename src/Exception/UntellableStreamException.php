<?php

declare(strict_types=1);

namespace Intriro\Stream\Exception;

use RuntimeException;

class UntellableStreamException extends RuntimeException implements ExceptionInterface
{
    public static function dueToClosedStream(): self
    {
        return new self('Cannot tell position from a closed stream.');
    }
}
