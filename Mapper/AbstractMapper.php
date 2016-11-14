<?php

namespace Vizzle\VizzleBundle\Mapper;

use Vizzle\VizzleBundle\Mapper\Exceptions\InvalidMappingException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;

abstract class AbstractMapper implements MapperInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * @var array Paths for mapping
     */
    protected $paths = [];

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var boolean
     */
    protected $debug;

    /**
     * @var array
     */
    protected static $loadFiles = [];

    /**
     * Process class.
     *
     * @param \ReflectionClass $reflectionClass Class
     *
     * @return array|null
     * @throws InvalidMappingException
     */
    abstract public function processReflectionClass($reflectionClass);

    /**
     * Mapper constructor.
     */
    public function __construct()
    {
        $this->reader = new AnnotationReader(new DocParser());
    }

    /**
     * Get annotation reader.
     *
     * @return AnnotationReader
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * Add path for mapping rpc methods.
     *
     * @param string $path
     *
     * @return void
     */
    public function addPath($path)
    {
        if ($path[0] === '@' & $this->container !== null) {
            try {
                $path = $this->container->get('kernel')->locateResource($path);
            } catch (\Exception $e) {
                return;
            }
        }

        if (is_dir($path)) {
            $this->paths[] = $path;
        }
    }

    /**
     * Get all metadata from mapping path.
     *
     * @return array
     * @throws InvalidMappingException
     */
    public function getMetadata()
    {
        if (!empty($this->meta)) {
            return $this->meta;
        }

        $meta = [];

        // Process dirs
        foreach ($this->paths as $path) {
            $meta += $this->getPathMetadata($path);
        }

        $this->meta = $meta;

        return $meta;
    }

    /**
     * Get metadata for all files in path.
     *
     * @param string $path Mapping path
     *
     * @return array
     * @throws InvalidMappingException
     */
    public function getPathMetadata($path)
    {
        $metadata = [];

        if (is_dir($path)) {

            $dir = new \DirectoryIterator($path);

            foreach ($dir as $file) {

                if ($file->isFile()) {

                    if ($array = $this->getFileMetadata($file->getRealPath())) {

                        foreach ($array as $fileMetadata) {

                            $metadata[$fileMetadata['class']] = $fileMetadata;

                        }

                    }

                }

                if ($file->isDir() && !$file->isDot()) {
                    $this->getPathMetadata($file->getRealPath());
                }

            }

        }

        return $metadata;
    }

    /**
     * Get metadata from object.
     *
     * @param object $object
     *
     * @return array|null
     * @throws InvalidMappingException
     */
    public function getObjectMetadata($object)
    {
        $reflection = new \ReflectionObject($object);

        return $this->getClassMetadata($reflection->getName());
    }

    /**
     * Get metadata from class.
     *
     * @param string $class Class
     *
     * @return array|null
     * @throws InvalidMappingException
     */
    public function getClassMetadata($class)
    {
        if (array_key_exists($class, $this->meta)) {
            return $this->meta[$class];
        }

        if (class_exists($class)) {
            return $this->processReflectionClass(new \ReflectionClass($class));
        }

        return null;
    }

    /**
     * Get metadata from file.
     *
     * @param string $file File
     *
     * @return array|null
     * @throws InvalidMappingException
     */
    public function getFileMetadata($file)
    {
        if (file_exists($file)) {

            // If file already process?
            if (array_key_exists($file, self::$loadFiles)) {
                return self::$loadFiles[$file];
            }

            $meta    = [];
            $classes = get_declared_classes();

            include_once $file;

            // Process find class in file, foreach for extend
            foreach (array_diff(get_declared_classes(), $classes) as $class) {
                if ($data = $this->getClassMetadata($class)) {
                    $meta[] = $data;
                }
            }

            self::$loadFiles[$file] = $meta;

            return $meta;
        }

        return null;
    }
}
