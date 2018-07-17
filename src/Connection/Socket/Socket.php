<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Connection\Socket;

use Psr\Http\Message\StreamInterface;
use TBolier\RethinkQL\Connection\OptionsInterface;
use TBolier\RethinkQL\Connection\Socket\Exception;

class Socket implements StreamInterface
{
    /**
     * @var resource
     */
    private $stream;

    /**
     * @var int
     */
    private $tellPos = 0;

    /**
     * @var bool
     */
    private $nullTerminated = false;

    /**
     * @throws Exception
     */
    public function __construct(OptionsInterface $options)
    {
        $this->openStream(
            ($options->isSsl() ? 'ssl' : 'tcp').'://'.$options->getHostname().':'.$options->getPort(),
            $options->getTimeout(),
            $options->getTimeoutStream()
        );
    }

    /**
     * @throws Exception
     */
    private function openStream(string $remote_socket, float $timeout, int $timeoutStream): void
    {
        $stream = stream_socket_client(
            $remote_socket,
            $errno,
            $errstr,
            $timeout,
            STREAM_CLIENT_CONNECT
        );

        if (!$stream) {
            throw new Exception('Failed to create a socket stream.');
        }

        $this->stream = $stream;
        stream_set_timeout($this->stream, $timeoutStream);
    }

    public function __toString(): string
    {
        try {
            return $this->getContents();
        } catch (\Exception $e) {
            return '';
        }
    }

    public function close(): void
    {
        fclose($this->stream);
        $this->stream = null;
    }

    public function detach(): void
    {
        $this->close();
    }

    public function getSize(): ?int
    {
        return null;
    }

    public function tell(): int
    {
        return $this->tellPos;
    }

    public function eof(): bool
    {
        return feof($this->stream);
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        throw new Exception('Cannot seek a socket stream');
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        return $this->stream ? true : false;
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \RuntimeException on failure.
     */
    public function write($string)
    {
        if (!$this->isWritable()) {
            throw new \RuntimeException('The stream is not writable.');
        }

        $writeLength = \strlen($string);
        $this->tellPos = $writeLength;

        $bytesWritten = 0;
        while ($bytesWritten < $writeLength) {
            $result = fwrite($this->stream, substr($string, $bytesWritten));
            if ($result === false || $result === 0) {
                $this->detach();
                if ($this->getMetadata('timed_out')) {
                    throw new Exception(
                        'Timed out while writing to socket. Disconnected. '
                        . 'Call setTimeout(seconds) on the connection to change '
                        . 'the timeout.'
                    );
                }
                throw new Exception('Unable to write to socket. Disconnected.');
            }
            $bytesWritten += $result;
            $this->tellPos -= $bytesWritten;
        }

        return $bytesWritten;
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable()
    {
        return $this->stream ? true : false;
    }

    public function read($length)
    {
        $this->tellPos = 0;
        $s = '';

        if ($length === -1) {
            while (true) {
                $char = $this->getContent(1);

                // skip initial null-terminated byte
                if ($s === '' && $char === \chr(0)) {
                    continue;
                }

                // reach a null-terminated byte, stop the stream.
                if ($char === '' || $char === \chr(0)) {
                    $this->nullTerminated = true;
                    break;
                }

                $s .= $char;
                $this->tellPos += $length - \strlen($s);
            }

            return $s;
        }

        while (\strlen($s) < $length) {
            $s .= $this->getContent($length - \strlen($s));
            $this->tellPos += $length - \strlen($s);
        }

        return $s;
    }

    /**
     * @throws Exception
     */
    private function getContent($length): string
    {
        $string = stream_get_contents($this->stream, $length);
        if ($string === false || $this->eof()) {
            if ($this->getMetadata('timed_out')) {
                throw new Exception(
                    'Timed out while reading from socket. Disconnected. '
                    . 'Call setTimeout(seconds) on the connection to change '
                    . 'the timeout.'
                );
            }
        }

        return $string;
    }

    public function getContents()
    {
        $result = '';
        while (!$this->eof() && !$this->nullTerminated) {
            $result .= $this->read(-1);
        }

        $this->nullTerminated = false;

        return $result;
    }

    public function getMetadata($key = null)
    {
        $meta = stream_get_meta_data($this->stream);

        return $meta[$key] ?? $meta;
    }
}
