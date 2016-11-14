<?php

namespace Vizzle\VizzleBundle;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as HttpKernel;

/**
 * Vizzle kernel.
 *
 * Based on Symfony HttpKernel and MicroKernelTrait.
 */
abstract class VizzleKernel extends HttpKernel
{
    use MicroKernelTrait;

    const VERSION = '0.1.0';

    /**
     * Get kernel name.
     *
     * @return string
     */
    public function getName()
    {
        return 'vizzle';
    }

    /**
     * Get kernel ip.
     *
     * @return string
     */
    public function getIp()
    {
        if ($this->getContainer() && $this->getContainer()->hasParameter('vizzle.ip')) {
            if ($ip = $this->getContainer()->getParameter('vizzle.ip')) {
                return $ip;
            }
        }

        $ip = gethostbyname(gethostname());

        return $ip === gethostname() ? '127.0.0.1' : $ip;
    }

    /**
     * Get kernel version.
     *
     * @return string
     */
    public function getVersion()
    {
        return VizzleKernel::VERSION . ' (' . HttpKernel::VERSION . ')';
    }

    /**
     * Get console cmd for execute.
     *
     * @return string
     */
    public function getConsoleCmd()
    {
        if (VizzleKernel::VERSION_ID >= 30000) {
            $consolePath = realpath($this->getRootDir() . '/../bin/console');
        } else {
            $consolePath = realpath($this->getRootDir() . '/console');
        }

        return $consolePath;
    }

    /**
     * Gets the container class.
     *
     * @return string The container class
     */
    protected function getContainerClass()
    {
        return $this->name . 'ProjectContainer';
    }
}
