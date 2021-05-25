<?php

declare(strict_types=1);

namespace Intriro\Stream;

use Intriro\Stream\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{
    /**
     * @test
     */
    function it_should_create_from_valid_resource(): void
    {
        $resource = fopen('php://temp', 'rw+');

        $stream = Stream::createFromResource($resource);

        $this->assertIsResource($stream->getResource());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
    }

    /**
     * @test
     */
    function it_should_not_create_from_invalid_resource(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Stream::createFromResource(
            imagecreate(1, 1)
        );
    }

    function test_create_from_url(): void
    {
        $stream = Stream::createFromUrl('php://temp', 'r');

        $this->assertIsResource($stream->getResource());
        $this->assertTrue($stream->isReadable());
    }

    /**
     * @test
     *
     * @dataProvider readableProvider
     */
    function it_should_be_readable_if_mode_is_correct(string $target, string $mode): void
    {
        $stream = Stream::createFromUrl($target, $mode);

        $this->assertTrue($stream->isReadable());
    }

    /**
     * @test
     *
     * @dataProvider unreadableProvider
     */
    function it_should_be_unreadable_if_mode_is_not_correct(string $target, string $mode): void
    {
        $stream = Stream::createFromUrl($target, $mode);

        $this->assertFalse($stream->isReadable());
    }

    /**
     * @test
     *
     * @dataProvider readableProvider
     */
    function it_should_be_unreadable_if_resource_is_closed(string $target, string $mode): void
    {
        $stream = Stream::createFromUrl($target, $mode);

        fclose($stream->getResource());

        $this->assertFalse($stream->isReadable());
    }

    /**
     * @test
     *
     * @dataProvider readableProvider
     */
    function it_should_be_open_if_resource_is_valid(string $target, string $mode): void
    {
        $stream = Stream::createFromUrl($target, $mode);

        $this->assertTrue($stream->isOpen());
    }

    /**
     * @test
     *
     * @dataProvider readableProvider
     */
    function it_should_not_be_closed_if_resource_is_valid(string $target, string $mode): void
    {
        $stream = Stream::createFromUrl($target, $mode);

        $this->assertFalse($stream->isClosed());
    }

    /**
     * @test
     */
    function it_should_close_handle_on_destruct(): void
    {
        $stream = Stream::createFromUrl('php://temp', 'r');
        $resource = $stream->getResource();

        unset($stream);

        $this->assertFalse(is_resource($resource));
    }

    function readableProvider(): array
    {
        return [
            ['php://temp', 'r', true],
            ['php://temp', 'rw', true],
            ['php://temp', 'w', true],
        ];
    }

    function unreadableProvider(): array
    {
        return [
            ['php://temp', 'a', false],
        ];
    }
}
