# Intriro Stream

Provides a simple abstraction over streams of data.

## Installation

```bash
composer require intriro/stream
```

## Documentation

Stream object by themselves don't have any methods for reading or writing. They just hold a reference to a PHP resource with some convenience methods to get information about the status of the stream.

### Create a stream

You can create streams from an existing resource.
```
$resource = fopen('/home/test/file.txt', 'rw+');

$stream = Stream::createFromResource($resource);
```

You can also create streams directly from a filename or a URL-style protocol.
The URL can be in the format that is supported by [fopen](https://www.php.net/manual/en/function.fopen.php).

```
$stream = Stream::createFromFilename('file:///home/test/file.txt', 'r');
$stream = Stream::createFromUrl('php://temp', 'r');
```

You can also use the convenience methods provided by the `StreamFactory` or `IoStreamFactory` to create streams.

```
$factory = new StreamFactory();
$stream = $streamFactory->filename(/home/test/file.txt', 'rw+');

$ioStreamFactory = new IoStreamFactory();
$stream = $ioStreamFactory->temp();
$stream = $ioStreamFactory->memory();
$stream = $ioStreamFactory->stdin();
$stream = $ioStreamFactory->stdout();
$stream = $ioStreamFactory->stderr();
```

### Reading and writing to a stream

Manipulation of streams is done through readers and writers.

#### Reading from a stream

```
$reader = new Reader(
    Stream::createFromFilename('/home/test/file.txt', 'r')
);

$reader->read(100); // will read 100 bytes from the stream


// Reading lines from a stream
$reader = new LineReader(
    Stream::createFromFilename('/home/test/file.txt', 'r')
);

while($line = $reader->readLine()) {
    // do something
}

```

#### Writing to a stream
```
$ioStreamFactory = new IoStreamFactory();

$writer = new Writer(
    $ioStreamFactory->temp()
);

$writer->write('some text');
```
