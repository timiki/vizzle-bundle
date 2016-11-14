<?php

namespace Vizzle\VizzleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log table.
 *
 * @ORM\Entity()
 * @ORM\Table(name="VLog")
 */
class Log
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Log datetime.
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    /**
     * Log channel.
     *
     * @ORM\Column(name="server", type="string")
     */
    private $server;

    /**
     * Log env.
     *
     * @ORM\Column(name="env", type="string")
     */
    private $env;

    /**
     * Log channel.
     *
     * @ORM\Column(name="channel", type="string")
     */
    private $channel;

    /**
     * Log level.
     *
     * @ORM\Column(name="level", type="smallint")
     */
    private $level;

    /**
     * Log level name.
     *
     * @ORM\Column(name="levelName", type="string")
     */
    private $levelName;

    /**
     * Log message.
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * Log context data.
     *
     * @ORM\Column(name="context", type="array")
     */
    private $context;

    /**
     * Log extra data.
     *
     * @ORM\Column(name="extra", type="array")
     */
    private $extra;

    /**
     * Log formatted.
     *
     * @ORM\Column(name="formatted", type="text")
     */
    private $formatted;

    //


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return Log
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set server
     *
     * @param string $server
     *
     * @return Log
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set env
     *
     * @param string $env
     *
     * @return Log
     */
    public function setEnv($env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * Get env
     *
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Set channel
     *
     * @param string $channel
     *
     * @return Log
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Log
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set levelName
     *
     * @param string $levelName
     *
     * @return Log
     */
    public function setLevelName($levelName)
    {
        $this->levelName = $levelName;

        return $this;
    }

    /**
     * Get levelName
     *
     * @return string
     */
    public function getLevelName()
    {
        return $this->levelName;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Log
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set context
     *
     * @param array $context
     *
     * @return Log
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set extra
     *
     * @param array $extra
     *
     * @return Log
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Set formatted
     *
     * @param string $formatted
     *
     * @return Log
     */
    public function setFormatted($formatted)
    {
        $this->formatted = $formatted;

        return $this;
    }

    /**
     * Get formatted
     *
     * @return string
     */
    public function getFormatted()
    {
        return $this->formatted;
    }
}
