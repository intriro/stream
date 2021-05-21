<?php

declare(strict_types=1);

namespace Intriro\Stream;

use Intriro\Stream\Exception\UnreadableStreamException;

class LineReader extends Reader
{
    public function readLine(): ?string
    {
        if ($this->stream->isClosed()) {
            throw UnreadableStreamException::dueToClosedStream();
        }

        if (!$this->stream->isReadable()) {
            throw UnreadableStreamException::dueToConfiguration();
        }

        $line = fgets($this->stream->getResource());

        if (false === $line) {
            return null;
        }

        return $line;
    }
}
