<?php

namespace Vizzle\VizzleBundle\Mapper;

interface MapperAwareInterface
{
    /**
     * Set the mapper.
     *
     * @param MapperInterface $mapper
     */
    public function setMapper(MapperInterface $mapper = null);

    /**
     * Get the mapper.
     *
     * @return MapperInterface|null
     */
    public function getMapper();
}
