<?php
namespace Application\Bundle\DefaultBundle\Publisher;

use Gelf\Message;
use Gelf\MessagePublisher as BasePublisher;

/**
 * Class MessagePublisher
 *
 * Send messages to Graylog server
 */
class MessagePublisher extends BasePublisher
{
    /**
     * @var string $fromName From name
     */
    private $fromName;

    /**
     * Constructor
     *
     * @param string $fromName
     * @param string $hostname
     * @param int    $port
     * @param int    $chunkSize
     */
    public function __construct($fromName, $hostname, $port = self::GRAYLOG2_DEFAULT_PORT, $chunkSize = self::CHUNK_SIZE_WAN)
    {
        parent::__construct($hostname, $port, $chunkSize);
        $this->fromName = $fromName;
    }

    /**
     * @param Message $message
     *
     * @return bool
     */
    public function publish(Message $message)
    {
        $message->setHost($this->fromName);

        return parent::publish($message);
    }
}
