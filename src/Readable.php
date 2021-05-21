<?php

declare(strict_types=1);

namespace Intriro\Stream;

use Intriro\Stream\Exception\RuntimeException;
use Intriro\Stream\Exception\UnreadableStreamException;
use Intriro\Stream\Exception\UnseekableStreamException;
use Intriro\Stream\Exception\UntellableStreamException;
use const SEEK_SET;

interface Readable
{
    /**
     * @throws UnreadableStreamException
     */
    public function read(int $length): string;

    /**
     * @throws UnseekableStreamException
     */
    public function seek(int $offset, int $whence = SEEK_SET): void;

    /**
     * @throws UnseekableStreamException
     */
    public function rewind(): void;

    public function close(): void;

    /**
     * @throws UntellableStreamException
     */
    public function tell(): int;

    /**
     * @throws RuntimeException
     */
    public function eof(): bool;
}
