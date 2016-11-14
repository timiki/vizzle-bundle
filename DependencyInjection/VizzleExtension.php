<?php

namespace Vizzle\VizzleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class VizzleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        // Set server name
        if (!isset($config['server']) || empty($config['server'])) {
            $config['server'] = gethostname();
        }

        // Set params from configs.
        $this->setParameters($config, 'vizzle', $container);

        // Load services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Set params.
     *
     * @param array            $config
     * @param string           $root Root config name in configs array.
     * @param ContainerBuilder $container
     */
    public function setParameters($config, $root, $container)
    {
        $processParam = function ($param, $parent) use (&$container, &$processParam) {

            foreach ($param as $key => $value) {

                if (is_array($value)) {
                    $processParam($value, empty($parent) ? $key : $parent . '.' . $key);
                } else {
                    $container->setParameter(empty($parent) ? $key : $parent . '.' . $key, $value);
                }

            }

        };

        $processParam($config, $root);
    }
}
