<?php

declare(strict_types=1);

namespace Intriro\Stream\Exception;

use RuntimeException;

class UnwritableStreamException extends RuntimeException implements ExceptionInterface
{
    public static function dueToConfiguration(): self
    {
        return new self('Stream is not writable.');
    }

    public static function dueToClosedStream(): self
    {
        return new self('Cannot write to a closed stream.');
    }

    public static function dueToPhpError(): self
    {
        return new self('Error writing to stream.');
    }
}
