<?php

declare(strict_types=1);

namespace Intriro\Stream;

use Intriro\Stream\Exception\UnreadableStreamException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ReaderTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $stream;

    public function setUp(): void
    {
        $this->stream = $this->prophesize(Stream::class);
    }
 
    /**
     * @test
     */
    function it_should_not_create_if_stream_is_closed(): void
    {
        $this->expectException(UnreadableStreamException::class);

        $this->given_stream_is_closed();

        new Reader(
            $this->stream->reveal()
        );
    }

    /**
     * @test
     */
    function it_should_not_create_if_stream_is_not_readable(): void
    {
        $this->expectException(UnreadableStreamException::class);

        $this->given_stream_is_open();
        $this->given_stream_is_not_readable();

        new Reader(
            $this->stream->reveal()
        );
    }

    /**
     * @test
     */
    function it_should_not_read_if_stream_is_closed(): void
    {
        $this->expectException(UnreadableStreamException::class);

        $this->given_stream_is_closed();

        $reader = new Reader(
            $this->stream->reveal()
        );

        $reader->read(10);
    }

    /**
     * @test
     */
    function it_should_not_read_if_stream_is_not_readable(): void
    {
        $this->expectException(UnreadableStreamException::class);

        $this->given_stream_is_open();
        $this->given_stream_is_not_readable();

        $reader = new Reader(
            $this->stream->reveal()
        );

        $reader->read(10);
    }

    protected function given_stream_is_closed(): void
    {
        $this
            ->stream
            ->isOpen()
            ->willReturn(false)
        ;

        $this
            ->stream
            ->isClosed()
            ->willReturn(true)
        ;
    }

    protected function given_stream_is_open(): void
    {
        $this
            ->stream
            ->isOpen()
            ->willReturn(true)
        ;

        $this
            ->stream
            ->isClosed()
            ->willReturn(false)
        ;

    }

    protected function given_stream_is_not_readable(): void
    {
        $this
            ->stream
            ->isReadable()
            ->willReturn(false)
        ;
    }
}
