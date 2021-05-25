<?php

declare(strict_types=1);

namespace Intriro\Stream\Factory;

use Intriro\Stream\Exception\InvalidArgumentException;
use Intriro\Stream\Stream;

class StreamFactory
{
    /**
     * @throws InvalidArgumentException
     */
    public function filename(string $filename, string $mode): Stream
    {
        return Stream::createFromFilename(
            $filename,
            $mode
        );
    }
}
