<?php

declare(strict_types=1);

namespace Intriro\Stream;

interface Writable
{
    public function write(string $string): int;

    public function close(): void;
}
