<?php

namespace Vizzle\VizzleBundle\Mapper;

trait MapperAwareTrait
{
    /**
     * Mapper.
     *
     * @var MapperInterface
     */
    protected $mapper;

    /**
     * Set the mapper.
     *
     * @param MapperInterface $mapper
     */
    public function setMapper(MapperInterface $mapper = null)
    {
        $this->mapper = $mapper;
    }

    /**
     * Get the mapper.
     *
     * @return MapperInterface|null
     */
    public function getMapper()
    {
        return $this->mapper;
    }
}
