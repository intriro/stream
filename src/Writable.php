<?php

declare(strict_types=1);

namespace Intriro\Stream;

use Intriro\Stream\Exception\UnwritableStreamException;

interface Writable
{
    /**
     * @throws UnwritableStreamException
     */
    public function write(string $string): int;

    public function close(): void;
}
