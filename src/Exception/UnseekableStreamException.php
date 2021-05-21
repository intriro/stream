<?php

declare(strict_types=1);

namespace Intriro\Stream\Exception;

use RuntimeException;

class UnseekableStreamException extends RuntimeException implements Exception
{
    public static function dueToConfiguration(): self
    {
        return new self('Stream is not seekable.');
    }

    public static function dueToClosedStream(): self
    {
        return new self('Cannot seek a closed stream.');
    }

    public static function dueToPhpError(): self
    {
        return new self('Error seeking within stream.');
    }
}
