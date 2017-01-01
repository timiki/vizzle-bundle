<?php

namespace Vizzle\VizzleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;

class StopCommand extends ContainerAwareCommand
{
    /**
     * @var SymfonyStyle
     */
    protected $io;

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

    /**
     * Initializes the command just after the input has been validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title('Stop Vizzle...');

        $container = $this->getContainer();
        $manager   = $container->get('vizzle.service.manager');
        $mapper    = $this->getContainer()->get('vizzle.service.mapper');

        foreach ($mapper->getMetadata() as $meta) {

            if ($manager->isServiceRun($meta['name'])) {

                $command = $this->getApplication()->find('service:stop');
                $command->run(
                    new ArrayInput(
                        [
                            'service' => $meta['name'],
                        ]
                    ),
                    $output
                );

            }

        }

        $output->writeln('');
        $this->io->success('Vizzle was successfully stopped.');
    }
}

