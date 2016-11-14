<?php

namespace Vizzle\VizzleBundle\Method\Logs;

use Timiki\Bundle\RpcServerBundle\Mapping as RPC;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Vizzle\VizzleBundle\Method\AbstractMethod;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Get logs list.
 *
 * @RPC\Method("logs.get")
 */
class GetMethod extends AbstractMethod implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @RPC\Param()
     * @Assert\GreaterThan(0)
     * @Assert\Type("numeric")
     */
    protected $limit = 100;

    /**
     * @Rpc\Execute()
     */
    public function execute()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $qb = $em->getRepository('VizzleBundle:Log')->createQueryBuilder('log');
        $qb->addOrderBy('log.datetime', 'DESC');

        $qb->setMaxResults($this->limit);

        return $this->serialize(
            $qb->getQuery()->execute()
        );
    }
}