<?php

namespace Vizzle\VizzleBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Vizzle\ServiceBundle\Mapping;
use Vizzle\VizzleBundle\Process\ProcessUtils;

/**
 * @Mapping\Process(
 *     name="server:web",
 *     description="Build-in web server.",
 *     mode="AUTO",
 *     lifetime=0
 * )
 */
class WebService implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var ProcessUtils
     */
    protected $processUtils;

    public function __construct()
    {
        $this->processUtils = new ProcessUtils();
    }

    /**
     * @Mapping\OnStart()
     */
    public function onStart()
    {
        $cmd = 'php ' . $this->container->get('kernel')->getConsoleCmd() . $this->getStopCmd();
        $this->processUtils->runBackground($cmd);

        $cmd = 'php ' . $this->container->get('kernel')->getConsoleCmd() . $this->getStartCmd();
        $this->processUtils->runBackground($cmd);
    }

    /**
     * @Mapping\OnStop()
     */
    public function onStop()
    {
        $cmd = 'php ' . $this->container->get('kernel')->getConsoleCmd() . $this->getStopCmd();
        $this->processUtils->runBackground($cmd);
    }

    /**
     * Get stop cmd.
     */
    public function getStopCmd()
    {
        $cmd = ' server:stop ' . $this->container->getParameter('vizzle.web.path');

        // Is debug
        if ($this->container->get('kernel')->isDebug()) {
            $cmd .= ' --debug';
        }

        return $cmd;
    }

    /**
     * Get start cmd.
     */
    public function getStartCmd()
    {
        $cmd = ' server:start ' . $this->container->getParameter('vizzle.web.path');

        // Is debug
        if ($this->container->get('kernel')->isDebug()) {
            $cmd .= ' --debug';
        }

        return $cmd;
    }

    /**
     * Is web service enabled
     */
    public function isEnabled()
    {
        if ($this->container->hasParameter('vizzle.web.enabled')) {
            return (boolean)$this->container->getParameter('vizzle.web.enabled');
        }

        return true;
    }
}
