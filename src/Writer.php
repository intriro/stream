<?php

declare(strict_types=1);

namespace Intriro\Stream;

use Intriro\Stream\Exception\UnwritableStreamException;

class Writer implements Writable
{
    private Stream $stream;

    /**
     * @throws UnwritableStreamException
     */
    public function __construct(Stream $stream)
    {
        if ($stream->isClosed()) {
            throw UnwritableStreamException::dueToClosedStream();
        }

        if ($stream->isWritable()) {
            throw UnwritableStreamException::dueToConfiguration();
        }

        $this->stream = $stream;
    }

    /**
     * @throws UnwritableStreamException
     */
    public function write(string $string): int
    {
        if ($this->stream->isClosed()) {
            throw UnwritableStreamException::dueToClosedStream();
        }

        if ($this->stream->isWritable()) {
            throw UnwritableStreamException::dueToConfiguration();
        }

        $result = fwrite($this->stream->getResource(), $string);

        if (false === $result) {
            $this->close();

            throw UnwritableStreamException::dueToPhpError();
        }

        return $result;
    }

    public function close(): void
    {
        $this->stream->close();
    }
}
