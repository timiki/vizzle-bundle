<?php

namespace Vizzle\VizzleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;

class RestartCommand extends ContainerAwareCommand
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
        $this->setName('vizzle:restart')
            ->setDescription('Restart vizzle')
            ->setHelp(<<<EOT
The <info>vizzle:restart</info> command restart all services.
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
        $this->io->title('Restart Vizzle...');

        $command = $this->getApplication()->find('vizzle:stop');
        $command->run(new ArrayInput([]), $output);

        sleep(5);

        $command = $this->getApplication()->find('vizzle:start');
        $command->run(new ArrayInput([]), $output);
    }
}

