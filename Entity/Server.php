<?php

namespace Vizzle\VizzleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server table
 *
 * @ORM\Entity()
 * @ORM\Table(name="VServer")
 * @ORM\HasLifecycleCallbacks()
 */
class Server
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Server name (by default use hostname).
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Server host.
     *
     * @ORM\Column(type="string")
     */
    private $host;

    /**
     * Server ip.
     *
     * @ORM\Column(type="string")
     */
    private $ip;

    /**
     * Server version.
     *
     * @ORM\Column(type="string")
     */
    private $version;

    /**
     * Server env.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $env = null;

    /**
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * On PrePersist
     *
     * @ORM\PreFlush()
     */
    public function onPreFlush()
    {
        $this->updatedAt = new \DateTime();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Server
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set host
     *
     * @param string $host
     *
     * @return Server
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Server
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return Server
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set env
     *
     * @param string $env
     *
     * @return Server
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Server
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
