<?php

declare(strict_types=1);

namespace Intriro\Stream;

use Intriro\Stream\Exception\InvalidArgumentException;

class StreamFactory
{
    private const TEMP_FILE_MAX_MEMORY = 2 * 1024 * 1024;

    public function temporaryFile(?int $maxMemory = self::TEMP_FILE_MAX_MEMORY): Stream
    {
        if ($maxMemory < 0) {
            throw new InvalidArgumentException('Max memory cannot be negative');
        }

        return Stream::createFromTarget(
            sprintf('php://temp/maxmemory:%s', self::TEMP_FILE_MAX_MEMORY),
            'rw'
        );
    }

    public function memory(): Stream
    {
        return Stream::createFromTarget(
            'php://memory',
            'rw'
        );
    }

    public function localFile(string $filename, string $mode): Stream
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('File "%s" does not exist.');
        }

        return Stream::createFromTarget(
            sprintf('file://%s', $filename),
            $mode
        );
    }
}
