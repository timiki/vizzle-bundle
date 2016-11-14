<?php

namespace Vizzle\VizzleBundle\Method\Servers;

use Timiki\Bundle\RpcServerBundle\Mapping as RPC;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Vizzle\VizzleBundle\Method\AbstractMethod;

/**
 * Get servers list.
 *
 * @RPC\Method("servers.get")
 */
class GetMethod extends AbstractMethod implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @Rpc\Execute()
     */
    public function execute()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        // Clear not active server
        $qb = $em->getRepository('VizzleBundle:Server')->createQueryBuilder('server');
        $qb->delete();
        $qb->where('server.updatedAt < :date');
        $qb->setParameter('date', (new \DateTime())->sub(new \DateInterval('PT1M')));

        $qb->getQuery()->execute();

        return $this->serialize(
            $em
                ->getRepository('VizzleBundle:Server')
                ->findAll()
        );
    }
}