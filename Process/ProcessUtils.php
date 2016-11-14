<?php

namespace Vizzle\VizzleBundle\Process;

use Symfony\Component\Process\Process;

class ProcessUtils
{
    /**
     * Find run cmd.
     *
     * @param string $cmd
     *
     * @return array Array of match find
     */
    public function findRunCmd($cmd)
    {
        $result = shell_exec('ps aux | grep "' . $cmd . '"');
        $result = explode(PHP_EOL, $result);

        unset($result[count($result) - 1]);

        return $result;
    }

    /**
     * Check is process exist by pid.
     *
     * @param integer $pid Process pid
     *
     * @return bool
     */
    public function isExistPid($pid)
    {
        switch (PHP_OS) {
            case 'Darwin':
            case 'Linux':

                $checkProcess = new Process('kill -0 ' . $pid);
                $checkProcess->run();

                return $checkProcess->getExitCode() === 0;
        }

        return false;
    }

    /**
     * Execute process in background.
     *
     * @param string $command Command for execute in background
     * @param string $output
     *
     * @return bool
     */
    public function runBackground($command, $output = null)
    {

        if (empty($output)) {
            $output = '/dev/null';
        }

        switch (PHP_OS) {
            case 'Darwin':
            case 'Linux':
                shell_exec($command . ' > ' . $output . ' 2>&1 &');

                return true;
        }

        return false;
    }

    /**
     * Terminate process.
     *
     * @param integer $pid Process pid
     *
     * @return bool
     */
    public function terminate($pid)
    {
        if ($this->isExistPid($pid)) {

            switch (PHP_OS) {
                case 'Darwin':
                case 'Linux':

                    $checkProcess = new Process('kill -15 ' . $pid);
                    $checkProcess->run();

                    return $checkProcess->getExitCode() === 0;
            }

        }

        return false;
    }
}