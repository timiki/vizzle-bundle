<?php

namespace Vizzle\VizzleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunCommand extends ContainerAwareCommand
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
        $this->setName('vizzle:run')
            ->setDescription('Run vizzle')
            ->addOption('clear', 'c', InputOption::VALUE_NONE, 'Clear cache before run')
            ->setHelp(<<<EOT
The <info>vizzle:start</info> command run all services process.
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
        $isRun    = true;
        $shutdown = function () use (&$isRun) {
            $isRun = false;
        };

        // Start
        $inputArray = [
            'command' => 'vizzle:start',
        ];

        if ($this->getContainer()->get('kernel')->isDebug()) {
            $inputArray['--debug'] = true;
        }

        if ($input->getOption('clear')) {
            $inputArray['--clear'] = true;
        }

        // Start
        $startCommand = $this->getApplication()->find('vizzle:start');
        $startCommand->run($input, $output);

        // Handler for signal
        foreach ([SIGINT, SIGTERM] as $signal) {
            pcntl_signal($signal, $shutdown);
        }

        $this->io->note('Press Ctrl+C for stop...');

        // Main loop
        while ($isRun) {
            pcntl_signal_dispatch();
            usleep(1e6);
        }

        $output->writeln('');

        // Stop
        $stopCommand = $this->getApplication()->find('vizzle:stop');
        $stopCommand->run(new ArrayInput([]), $output);
    }
}

