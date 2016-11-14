<?php

namespace Vizzle\VizzleBundle\Method;

use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

abstract class AbstractMethod
{
    /**
     * Serialize
     *
     * @param mixed             $data Data to serialize
     * @param null|string|array $groups
     *
     * @return array|object|\Symfony\Component\Serializer\Normalizer\scalar
     */
    public function serialize($data, $groups = null)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $serializer = new Serializer(
            [
                new PropertyNormalizer($classMetadataFactory),
                new DateTimeNormalizer('Y-m-d H:i:s'),
                new ObjectNormalizer($classMetadataFactory),
            ]
        );

        if (!empty($groups)) {
            return $serializer->normalize($data, null, ['groups' => (array)$groups]);
        }

        return $serializer->normalize($data, null);
    }
}