# Intriro Stream

Provides a simple abstraction over streams of data.

## Installation

```bash
composer require intriro/stream
```

**Note:**
This library is still under development, so the API can (and will) change.

## Documentation

Stream object by themselves don't have any methods for reading or writing. They just hold a reference to a PHP resource with some convenience methods to get information about the status of the stream.

### Create a stream

You can create streams from an existing resource.
```
$resource = fopen('/home/test/file.txt', 'rw+');

$stream = Stream::createFromResource($resource);
```

You can also create streams directly from a file name or URL.
The URL can be in the format that is supported by [fopen](https://www.php.net/manual/en/function.fopen.php).

```
$stream = Stream::createFromTarget('/home/test/file.txt', 'r');
$stream = Stream::createFromTarget('php://temp', 'r');
```

You can also use the convenience methods provided by the `StreamFactory` to create streams.

```
$stream = StreamFactory::temporaryFile();
$stream = StreamFactory::memory();
$stream = StreamFactory::localFile(/home/test/file.txt', 'rw+');
```

### Reading and writing to a stream

Manipulation of streams is done through readers and writers.

#### Reading from a stream

```
$reader = new Reader(
    Stream::createFromTarget('/home/test/file.txt', 'r')
);

$reader->read(100); // will read 100 bytes from the stream


// Reading lines from a stream
$reader = new LineReader(
    Stream::createFromTarget('/home/test/file.txt', 'r')
);

while($line = $reader->readLine()) {
    // do something
}

```

#### Writing to a stream
```
$writer = new Writer(
    Stream::createFromTarget('php://temp', 'rw+')
);

$reader->write('some text');

```
