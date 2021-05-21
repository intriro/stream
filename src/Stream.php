<?php

declare(strict_types=1);

namespace Intriro\Stream;

use Intriro\Stream\Exception\InvalidArgumentException;
use function fclose;
use function fopen;
use function is_resource;
use function stream_get_meta_data;
use const SEEK_CUR;

class Stream
{
    /**
     * @var resource|null A resource reference
     */
    private $resource;
    private bool $readable;
    private bool $writable;
    private bool $seekable;
    private bool $open;

    /**
     * @var array<string, <string, bool>> Hash of readable and writable stream types
     */
    private const READ_WRITE_HASH = [
        'read' => [
            'r' => true, 'w+' => true, 'r+' => true, 'x+' => true, 'c+' => true,
            'rb' => true, 'w+b' => true, 'r+b' => true, 'x+b' => true,
            'c+b' => true, 'rt' => true, 'w+t' => true, 'r+t' => true,
            'x+t' => true, 'c+t' => true, 'a+' => true,
        ],
        'write' => [
            'w' => true, 'w+' => true, 'rw' => true, 'r+' => true, 'x+' => true,
            'c+' => true, 'wb' => true, 'w+b' => true, 'r+b' => true,
            'x+b' => true, 'c+b' => true, 'w+t' => true, 'r+t' => true,
            'x+t' => true, 'c+t' => true, 'a' => true, 'a+' => true,
        ],
    ];

    /**
     * @param resource $resource
     */
    private function __construct($resource)
    {
        $this->resource = $resource;

        $meta = stream_get_meta_data($this->resource);

        $this->readable = isset(self::READ_WRITE_HASH['read'][$meta['mode']]);
        $this->writable = isset(self::READ_WRITE_HASH['write'][$meta['mode']]);
        $this->seekable = $meta['seekable'] && 0 === fseek($this->resource, 0, SEEK_CUR);
        $this->open = true;
    }

    /**
     * @param resource $resource
     */
    public static function createFromResource($resource): self
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('You have to provide a valid resource.');
        }

        if ('stream' !== get_resource_type($resource)) {
            throw new InvalidArgumentException('The provided resource is not a valid stream.');
        }

        return new self($resource);
    }

    public static function createFromTarget(string $target, string $mode): self
    {
        $error = null;
        $resource = null;

        if (\is_string($target)) {
            set_error_handler(static function ($e) use (&$error): void {
                if (E_WARNING !== $e) {
                    return;
                }

                $error = $e;
            });

            $resource = fopen($target, $mode);

            restore_error_handler();
        }

        if ($error) {
            throw new InvalidArgumentException('Invalid stream target provided');
        }

        return self::createFromResource($resource);
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close(): void
    {
        if ($this->isClosed()) {
            return;
        }

        fclose($this->resource);

        $this->resource = null;
    }

    public function isOpen(): bool
    {
        if (true === $this->open) {
            if (null === $this->resource || !is_resource($this->resource)) {
                $this->open = false;
            }
        }

        return $this->open;
    }

    public function isClosed(): bool
    {
        return !$this->isOpen();
    }

    public function isReadable(): bool
    {
        if ($this->isClosed()) {
            return false;
        }

        return $this->readable;
    }

    public function isWritable(): bool
    {
        if ($this->isClosed()) {
            return false;
        }

        return $this->writable;
    }

    public function isSeekable(): bool
    {
        if ($this->isClosed()) {
            return false;
        }

        return $this->seekable;
    }

    /**
     * @return resource|null the stream resource or null if the stream was closed
     */
    public function getResource()
    {
        return $this->resource;
    }
}
