<?php
declare(strict_types = 1);

/**
 * @license http://www.apache.org/licenses/ Apache License 2.0
 * @license https://github.com/danielmewes/php-rql Apache License 2.0
 * @Author Daniel Mewes https://github.com/danielmewes
 * @Author Timon Bolier https://github.com/tbolier
 *
 * The Handshake class contains parts of code copied from the original PHP-RQL library under the Apache License 2.0:
 * @see https://github.com/danielmewes/php-rql/blob/master/rdb/Handshake.php
 *
 * Stating the following changes have been done to the parts of the code copied in the Handshake Class:
 * - Amendments to code styles and control structures.
 * - Abstraction of code to new methods to improve readability.
 * - Removed obsolete code.
 */

namespace TBolier\RethinkQL\Connection\Socket;

use Psr\Http\Message\StreamInterface;

class Handshake implements HandshakeInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $protocolVersion = 0;

    /**
     * @var int
     */
    private $state;

    /**
     * @var string
     */
    private $myR;

    /**
     * @var string
     */
    private $clientFirstMessage;

    /**
     * @var string
     */
    private $serverSignature;

    /**
     * @var int
     */
    private $version;

    public function __construct(string $username, string $password, int $version)
    {
        $this->username = $username;
        $this->password = $password;
        $this->state = 0;
        $this->version = $version;
    }

    /**
     * @throws \RuntimeException
     * @throws Exception
     */
    public function hello(StreamInterface $stream): void
    {
        try {
            $handshakeResponse = null;
            while (true) {
                if (!$stream->isWritable()) {
                    throw new Exception('Not connected');
                }

                $this->checkResponse($handshakeResponse);

                try {
                    $msg = $this->nextMessage($handshakeResponse);
                } catch (Exception $e) {
                    $stream->close();
                    throw $e;
                }

                if ($msg === 'successful') {
                    break;
                }

                if ($msg !== '') {
                    $stream->write($msg);
                }

                // Read null-terminated response
                $handshakeResponse = $stream->getContents();
            }
        } catch (Exception $e) {
            $stream->close();
            throw $e;
        }
    }

    private function nextMessage(string $response = null): ?string
    {
        if ($response === null) {
            return $this->createHandshakeMessage();
        }

        switch ($this->state) {
            case 1:
                return $this->verifyProtocol($response);
            case 2:
                return $this->createAuthenticationMessage($response);
            case 3:
                return $this->verifyAuthentication($response);
            default:
                throw new Exception('Illegal handshake state');
        }
    }

    private function pkbdf2Hmac(string $password, string $salt, int $iterations): string
    {
        $t = hash_hmac('sha256', $salt."\x00\x00\x00\x01", $password, true);
        $u = $t;
        for ($i = 0; $i < $iterations - 1; ++$i) {
            $t = hash_hmac('sha256', $t, $password, true);
            $u ^= $t;
        }

        return $u;
    }

    private function createHandshakeMessage(): string
    {
        $this->myR = base64_encode(openssl_random_pseudo_bytes(18));
        $this->clientFirstMessage = 'n='.$this->username.',r='.$this->myR;

        $binaryVersion = pack('V', $this->version);

        $this->state = 1;

        return
            $binaryVersion
            . json_encode(
                [
                    'protocol_version' => $this->protocolVersion,
                    'authentication_method' => 'SCRAM-SHA-256',
                    'authentication' => 'n,,'.$this->clientFirstMessage,
                ]
            )
            . \chr(0);
    }

    private function verifyProtocol(?string $response): string
    {
        if (strpos($response, 'ERROR') === 0) {
            throw new Exception(
                'Received an unexpected reply. You may be attempting to connect to '
                . 'a RethinkDB server that is too old for this driver. The minimum '
                . 'supported server version is 2.3.0.'
            );
        }

        $json = json_decode($response, true);
        if ($json['success'] === false) {
            throw new Exception('Handshake failed: '.$json["error"]);
        }
        if ($this->protocolVersion > $json['max_protocol_version']
            || $this->protocolVersion < $json['min_protocol_version']) {
            throw new Exception('Unsupported protocol version.');
        }

        $this->state = 2;

        return '';
    }

    /**
     * @throws Exception
     */
    private function createAuthenticationMessage($response): string
    {
        $json = json_decode($response, true);
        if ($json['success'] === false) {
            throw new Exception('Handshake failed: '.$json['error']);
        }
        $serverFirstMessage = $json['authentication'];
        $authentication = [];
        foreach (explode(',', $json['authentication']) as $var) {
            $pair = explode('=', $var);
            $authentication[$pair[0]] = $pair[1];
        }
        $serverR = $authentication['r'];
        if (strpos($serverR, $this->myR) !== 0) {
            throw new Exception('Invalid nonce from server.');
        }
        $salt = base64_decode($authentication['s']);
        $iterations = (int) $authentication['i'];

        $clientFinalMessageWithoutProof = 'c=biws,r='.$serverR;
        $saltedPassword = $this->pkbdf2Hmac($this->password, $salt, $iterations);
        $clientKey = hash_hmac('sha256', 'Client Key', $saltedPassword, true);
        $storedKey = hash('sha256', $clientKey, true);

        $authMessage =
            $this->clientFirstMessage.','.$serverFirstMessage.','.$clientFinalMessageWithoutProof;

        $clientSignature = hash_hmac('sha256', $authMessage, $storedKey, true);

        $clientProof = $clientKey ^ $clientSignature;

        $serverKey = hash_hmac('sha256', 'Server Key', $saltedPassword, true);

        $this->serverSignature = hash_hmac('sha256', $authMessage, $serverKey, true);

        $this->state = 3;

        return
            json_encode(
                [
                    'authentication' => $clientFinalMessageWithoutProof.',p='.base64_encode($clientProof),
                ]
            )
            . \chr(0);
    }

    /**
     * @throws Exception
     */
    private function verifyAuthentication(string $response): string
    {
        $json = json_decode($response, true);
        if ($json['success'] === false) {
            throw new Exception('Handshake failed: '.$json['error']);
        }
        $authentication = [];
        foreach (explode(',', $json['authentication']) as $var) {
            $pair = explode('=', $var);
            $authentication[$pair[0]] = $pair[1];
        }

        $this->checkSignature(base64_decode($authentication['v']));

        $this->state = 4;

        return 'successful';
    }

    private function checkResponse(?string $handshakeResponse): void
    {
        if ($handshakeResponse !== null && preg_match(
            '/^ERROR:\s(.+)$/i',
            $handshakeResponse,
            $errorMatch
        )) {
            throw new Exception($errorMatch[1]);
        }
    }

    /**
     * @throws Exception
     */
    private function checkSignature(string $signature): void
    {
        if (!hash_equals($signature, $this->serverSignature)) {
            throw new Exception('Invalid server signature.');
        }
    }
}
