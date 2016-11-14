<?php

namespace Vizzle\VizzleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class RestartCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('vizzle:restart')
            ->setDescription('Restart vizzle')
            ->setHelp(<<<EOT
The <info>vizzle:restart</info> command restart all services.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->getApplication()->doRun(
            new ArrayInput(
                [
                    'command' => 'stop',
                ]
            ),
            $output
        );

        sleep(5);

        $this->getApplication()->doRun(
            new ArrayInput(
                [
                    'command' => 'start',
                ]
            ),
            $output
        );

    }
}

