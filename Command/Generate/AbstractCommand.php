<?php

namespace Vizzle\VizzleBundle\Command\Generate;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * Get the twig environment path to skeletons.
     *
     * @return string
     */
    abstract public function getTwigPath();

    /**
     * Transforms the given string to a new string valid as a PHP class name.
     *
     * @param string $string
     *
     * @return string The string transformed to be a valid PHP class name
     */
    public function classify($string)
    {
        return str_replace(' ', '', ucwords(strtr($string, '_-:', '   ')));
    }

    /**
     * Render skeleton template with parameters.
     *
     * @param $template
     * @param $parameters
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render($template, $parameters)
    {
        $twig = $this->getTwigEnvironment();

        return $twig->render($template, $parameters);
    }

    /**
     * Get the twig environment that will render skeletons.
     *
     * @return \Twig_Environment
     */
    public function getTwigEnvironment()
    {
        return new \Twig_Environment(
            new \Twig_Loader_Filesystem(
                [
                    $this->getTwigPath(),
                ]
            ),
            [
                'debug'            => true,
                'cache'            => false,
                'strict_variables' => true,
                'autoescape'       => false,
            ]
        );
    }

    /**
     * Render skeleton template with parameters to file.
     *
     * @param $template
     * @param $target
     * @param $parameters
     *
     * @return int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderFile($template, $target, $parameters)
    {
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }

        return file_put_contents($target, $this->render($template, $parameters));
    }
}
