<?php

namespace Vizzle\VizzleBundle\Command\Autoboot;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Vizzle\VizzleBundle\Command\Generate\AbstractCommand;

class DisabledCommand extends AbstractCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('autoboot:disabled')
            ->setDescription('Disabled autoboot')
            ->setHelp(<<<EOT
The <info>autoboot:disabled</info> command disabled autoboot vizzle on system startup.
EOT
            );
    }

    /**
     * Check if autoboot support.
     *
     * @return boolean
     */
    public function isSupport()
    {
        switch (PHP_OS) {
            case 'Linux':
                return true;
        }

        return false;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io         = new SymfonyStyle($input, $output);
        $filesystem = new Filesystem();

        if (!$this->isSupport()) {
            throw new \RuntimeException(
                sprintf(
                    'OS "%s" not supported for autoboot',
                    PHP_OS
                )
            );
        }

        switch (PHP_OS) {

            case 'Linux': {

                $pathToInitD = '/etc/init.d/vizzle';

                if (!$filesystem->exists($pathToInitD)) {
                    $io->success(
                        sprintf(
                            'Autoboot already disabled by path %s',
                            $pathToInitD
                        )
                    );

                    return 0;
                }

                // Check writable path
                if (!is_writable('/etc/init.d/')) {
                    throw new \RuntimeException('Directory /etc/init.d is not writable. Is you root?');
                }

                // Remove from rc.d
                $addProcess = new Process('update-rc.d -f vizzle remove');
                $addProcess->run();

                if (!$addProcess->isSuccessful()) {
                    throw new \RuntimeException('False run update-rc.d -f vizzle remove.');
                } else {
                    $filesystem->remove($pathToInitD);
                }

                break;
            }

        }

        return 0;
    }

    /**
     * Get the twig environment path to skeletons.
     *
     * @return string
     */
    public function getTwigPath()
    {
        return dirname(__DIR__) . '/../Resources/skeleton';
    }
}

