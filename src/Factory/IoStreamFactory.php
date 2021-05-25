<?php

declare(strict_types=1);

namespace Intriro\Stream\Factory;

use Intriro\Stream\Exception\InvalidArgumentException;
use Intriro\Stream\Stream;

class IoStreamFactory
{
    private const TEMP_FILE_MAX_MEMORY = 2 * 1024 * 1024;

    public function stdin(): Stream
    {
        return Stream::createFromUrl('php://stdin', 'r');
    }

    public function stdout(): Stream
    {
        return Stream::createFromUrl('php://stdout', 'w');
    }

    public function stderr(): Stream
    {
        return Stream::createFromUrl('php://stdout', 'w');
    }

    public function temp(?int $maxMemory = self::TEMP_FILE_MAX_MEMORY): Stream
    {
        if ($maxMemory < 0) {
            throw new InvalidArgumentException('Max memory cannot be negative');
        }

        return Stream::createFromUrl(
            sprintf('php://temp/maxmemory:%s', self::TEMP_FILE_MAX_MEMORY),
            'rw'
        );
    }

    public function memory(): Stream
    {
        return Stream::createFromUrl(
            'php://memory',
            'rw'
        );
    }
}
