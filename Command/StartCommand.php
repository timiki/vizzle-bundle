<?php

namespace Vizzle\VizzleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class StartCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('vizzle:start')
            ->setDescription('Start vizzle')
            ->addOption('clear', 'c', InputOption::VALUE_NONE, 'Clear cache on start')
            ->setHelp(<<<EOT
The <info>vizzle:start</info> command run all services process in backgrounds.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // If need clear cache.
        if ($input->getOption('clear')) {

            $this->getApplication()->doRun(
                new ArrayInput(
                    [
                        'command' => 'cache:clear',
                    ]
                ),
                $output
            );

        }

        $container = $this->getContainer();
        $manager   = $container->get('vizzle.service.manager');
        $mapper    = $this->getContainer()->get('vizzle.service.mapper');

        foreach ($mapper->getMetadata() as $meta) {

            if ($meta['mode'] == 'AUTO' && !$manager->isServiceRun($meta['name']) && $manager->isServiceEnabled($meta['name'])) {

                $input = [
                    'command' => 'service:start',
                    'service' => $meta['name'],
                ];

                // Is debug
                if ($this->getContainer()->get('kernel')->isDebug()) {
                    $input[] = '--debug';
                }

                $this->getApplication()->doRun(
                    new ArrayInput($input),
                    $output
                );

            }

        }

    }
}

