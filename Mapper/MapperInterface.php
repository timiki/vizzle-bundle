<?php

namespace Vizzle\VizzleBundle\Mapper;

use Vizzle\VizzleBundle\Mapper\Exceptions\InvalidMappingException;

/**
 * Mapper interface
 */
interface MapperInterface
{
    /**
     * Add path for mapping rpc methods.
     *
     * @param string $path
     *
     * @return void
     */
    public function addPath($path);

    /**
     * Get all metadata from mapping path.
     *
     * @return array
     * @throws InvalidMappingException
     */
    public function getMetadata();

    /**
     * Get metadata for all files in path.
     *
     * @param string $path Mapping path
     * @return array
     * @throws InvalidMappingException
     */
    public function getPathMetadata($path);

    /**
     * Get metadata from object.
     *
     * @param object $object
     * @return array|null
     * @throws InvalidMappingException
     */
    public function getObjectMetadata($object);

    /**
     * Get metadata from class.
     *
     * @param string $class Class
     * @return array|null
     * @throws InvalidMappingException
     */
    public function getClassMetadata($class);

    /**
     * Get metadata from file.
     *
     * @param string $file File
     * @return array|null
     * @throws InvalidMappingException
     */
    public function getFileMetadata($file);
}
