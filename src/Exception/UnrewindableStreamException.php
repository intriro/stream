<?php

declare(strict_types=1);

namespace Intriro\Stream\Exception;

use RuntimeException;

class UnrewindableStreamException extends RuntimeException implements Exception
{
    public static function forCallbackStream(): self
    {
        return new self('Callback streams cannot rewind position');
    }
}
