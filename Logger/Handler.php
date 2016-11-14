<?php

namespace Vizzle\VizzleBundle\Logger;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Vizzle\VizzleBundle\Entity\Log;

/**
 * Vizzle logger handler.
 */
class Handler implements HandlerInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    const DEFAULT_LEVEL = 200;

    protected $formatter;
    protected $processors = [];


    public function __construct()
    {
        $this->formatter = new LineFormatter();
    }

    /**
     * Checks whether the given record will be handled by this handler.
     *
     * This is mostly done for performance reasons, to avoid calling processors for nothing.
     *
     * Handlers should still check the record levels within handle(), returning false in isHandling()
     * is no guarantee that handle() will not be called, and isHandling() might not be called
     * for a given record.
     *
     * @param array $record Partial log record containing only a level key
     *
     * @return Boolean
     */
    public function isHandling(array $record)
    {
        if (isset($record['level'])) {
            return $this->getLevel() <= $record['level'];
        }

        return false;
    }

    /**
     * Handles a record.
     *
     * All records may be passed to this method, and the handler should discard
     * those that it does not want to handle.
     *
     * The return value of this function controls the bubbling process of the handler stack.
     * Unless the bubbling is interrupted (by returning true), the Logger class will keep on
     * calling further handlers in the stack with a given log record.
     *
     * @param  array $record The record to handle
     *
     * @return Boolean true means that this handler handled the record, and that bubbling is not permitted.
     *                        false means the record was either not processed or that this handler allows bubbling.
     */
    public function handle(array $record)
    {
        if ($this->isHandling($record) && $this->container) {

            $em                  = $this->container->get('doctrine.orm.entity_manager');
            $record              = $this->processRecord($record);
            $record['formatted'] = $this->getFormatter()->format($record);

            $log = new Log();

            $log->setMessage($record['message']);
            $log->setContext($record['context']);
            $log->setLevel($record['level']);
            $log->setLevelName($record['level_name']);
            $log->setChannel($record['channel']);
            $log->setDatetime($record['datetime']);
            $log->setExtra($record['extra']);
            $log->setFormatted($record['formatted']);
            $log->setServer($this->container->getParameter('vizzle.server'));
            $log->setEnv($this->container->getParameter('kernel.environment'));

            $em->persist($log);

            try {
                $em->persist($log);
                $em->flush($log);
            } catch (\Exception $e) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Handles a set of records at once.
     *
     * @param array $records The records to handle (an array of record arrays)
     */
    public function handleBatch(array $records)
    {
        foreach ($records as $record) {
            $this->handle($record);
        }
    }

    /**
     * Processes a record.
     *
     * @param  array $record
     *
     * @return array
     */
    protected function processRecord(array $record)
    {
        if ($this->processors) {
            foreach ($this->processors as $processor) {
                $record = call_user_func($processor, $record);
            }
        }

        return $record;
    }

    /**
     * Adds a processor in the stack.
     *
     * @param  callable $callback
     *
     * @return self
     */
    public function pushProcessor($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Processors must be valid callables (callback or object with an __invoke method), ' . var_export($callback, true) . ' given');
        }
        array_unshift($this->processors, $callback);

        return $this;
    }

    /**
     * Removes the processor on top of the stack and returns it.
     *
     * @return callable
     */
    public function popProcessor()
    {
        if (!$this->processors) {
            throw new \LogicException('You tried to pop from an empty processor stack.');
        }

        return array_shift($this->processors);
    }

    /**
     * Sets the formatter.
     *
     * @param  FormatterInterface $formatter
     *
     * @return self
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Gets the formatter.
     *
     * @return FormatterInterface
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Gets minimum logging level at which this handler will be triggered.
     *
     * @return int
     */
    public function getLevel()
    {
        if ($this->container && $this->container->hasParameter('vizzle.log.level')) {

            $level = Logger::toMonologLevel(
                $this->container->getParameter('vizzle.log.level')
            );

            if (is_integer($level)) {
                return $level;
            }

        }

        return self::DEFAULT_LEVEL;
    }
}