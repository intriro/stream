<?php

declare(strict_types=1);

namespace Intriro\Stream\Exception;

use RuntimeException;

class UnreadableStreamException extends RuntimeException implements ExceptionInterface
{
    public static function dueToConfiguration(): self
    {
        return new self('Stream is not readable.');
    }

    public static function dueToClosedStream(): self
    {
        return new self('Cannot read from a closed stream.');
    }

    public static function dueToPhpError(): self
    {
        return new self('Error reading stream.');
    }
}
