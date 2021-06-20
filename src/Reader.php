<?php

declare(strict_types=1);

namespace Intriro\Stream;

use Intriro\Stream\Exception\RuntimeException;
use Intriro\Stream\Exception\UnreadableStreamException;
use Intriro\Stream\Exception\UnseekableStreamException;
use Intriro\Stream\Exception\UntellableStreamException;
use function fread;
use function fseek;
use function var_export;
use const SEEK_SET;

class Reader implements Readable
{
    protected Stream $stream;

    /**
     * @throws UnreadableStreamException
     */
    public function __construct(Stream $stream)
    {
        if ($stream->isClosed()) {
            throw UnreadableStreamException::dueToClosedStream();
        }

        if (!$stream->isReadable()) {
            throw UnreadableStreamException::dueToConfiguration();
        }

        $this->stream = $stream;
    }

    /**
     * @throws UnreadableStreamException
     */
    public function read(int $length): string
    {
        if ($this->stream->isClosed()) {
            throw UnreadableStreamException::dueToClosedStream();
        }

        if (!$this->stream->isReadable()) {
            throw UnreadableStreamException::dueToConfiguration();
        }

        $result = fread($this->stream->getResource(), $length);

        if (false === $result) {
            $this->close();

            throw UnreadableStreamException::dueToPhpError();
        }

        return $result;
    }

    /**
     * @throws UnseekableStreamException
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if ($this->stream->isClosed()) {
            throw UnseekableStreamException::dueToClosedStream();
        }

        if (!$this->stream->isSeekable()) {
            throw UnseekableStreamException::dueToConfiguration();
        }

        if (-1 === fseek($this->stream->getResource(), $offset, $whence)) {
            throw new UnseekableStreamException(sprintf(
                'Unable to seek to stream position %s with whence %s',
                $offset,
                var_export($whence, true)
            ));
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function close(): void
    {
        $this->stream->close();
    }

    /**
     * @throws UntellableStreamException
     */
    public function tell(): int
    {
        if ($this->stream->isClosed()) {
            throw UntellableStreamException::dueToClosedStream();
        }

        if (false === $result = ftell($this->stream->getResource())) {
            throw new UntellableStreamException('Unable to determine stream position');
        }

        return $result;
    }

    public function eof(): bool
    {
        if ($this->stream->isClosed()) {
            throw new RuntimeException('Unable to check end of file due to a closed stream.');
        }

        return feof($this->stream->getResource());
    }
}
