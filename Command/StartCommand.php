<?php

namespace Vizzle\VizzleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;

class StartCommand extends ContainerAwareCommand
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
        $this->setName('vizzle:start')
            ->setDescription('Start vizzle')
            ->addOption('clear', 'c', InputOption::VALUE_NONE, 'Clear cache before start')
            ->setHelp(<<<EOT
The <info>vizzle:start</info> command start all services process in backgrounds.
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
        $this->io->title('Start Vizzle...');

        // If need clear cache.
        if ($input->getOption('clear')) {
            $this->io->success('Cache was successfully cleared.');
            $startCommand = $this->getApplication()->find('cache:clea');
            $startCommand->run(new ArrayInput([]), new NullOutput());
        }

        $container = $this->getContainer();
        $manager   = $container->get('vizzle.service.manager');
        $mapper    = $this->getContainer()->get('vizzle.service.mapper');

        foreach ($mapper->getMetadata() as $meta) {
            if ($meta['mode'] == 'AUTO' && !$manager->isServiceRun($meta['name']) && $manager->isServiceEnabled($meta['name'])) {

                $input = [
                    'service' => $meta['name'],
                ];

                // Is debug
                if ($this->getContainer()->get('kernel')->isDebug()) {
                    $input[] = '--debug';
                }

                $command = $this->getApplication()->find('service:start');
                $command->run(
                    new ArrayInput($input),
                    $output
                );

            }
        }

        $output->writeln('');
        $this->io->success('Vizzle was successfully started.');
    }
}

