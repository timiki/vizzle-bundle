<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vizzle\VizzleBundle\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Vizzle\VizzleBundle\VizzleKernel;

class Application extends BaseApplication
{
    /**
     * Constructor.
     *
     * @param KernelInterface $kernel A KernelInterface instance
     */
    public function __construct(KernelInterface $kernel)
    {

        // Inject kernel in parent;
        $reflection = new \ReflectionObject($this);

        $_kernel = $reflection->getParentClass()->getProperty('kernel');
        $_kernel->setAccessible(true);
        $_kernel->setValue($this, $kernel);
        $_kernel->setAccessible(false);

        $this->setName('Vizzle');
        $this->setVersion(VizzleKernel::VERSION . ' (' . Kernel::VERSION . ') - ' . $kernel->getName() . '/' . $kernel->getEnvironment() . ($kernel->isDebug() ? '/debug' : ''));
        $this->setDefaultCommand('list');
        $this->setHelperSet($this->getDefaultHelperSet());
        $this->setDefinition($this->getDefaultInputDefinition());

        foreach ($this->getDefaultCommands() as $command) {
            $this->add($command);
        }

        $this->getDefinition()->addOption(
            new InputOption('--debug', null, InputOption::VALUE_NONE, 'Switches on debug mode.')
        );
    }
}