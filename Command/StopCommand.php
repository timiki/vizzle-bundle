<?php

namespace Vizzle\VizzleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class StopCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('vizzle:stop')
            ->setDescription('Stop vizzle')
            ->setHelp(<<<EOT
The <info>vizzle:stop</info> command stop all run services process in backgrounds.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $manager   = $container->get('vizzle.service.manager');
        $mapper    = $this->getContainer()->get('vizzle.service.mapper');

        foreach ($mapper->getMetadata() as $meta) {

            if ($manager->isServiceRun($meta['name'])) {

                $this->getApplication()->doRun(
                    new ArrayInput(
                        [
                            'command' => 'service:stop',
                            'service' => $meta['name'],
                        ]
                    ),
                    $output
                );

            }

        }

    }
}

