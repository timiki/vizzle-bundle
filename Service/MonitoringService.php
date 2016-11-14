<?php

namespace Vizzle\VizzleBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Vizzle\ServiceBundle\Mapping;
use Vizzle\VizzleBundle\Entity\Server;

/**
 * @Mapping\Process(
 *     name="server:monitoring",
 *     description="Server monitoring service.",
 *     mode="AUTO"
 * )
 */
class MonitoringService implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var Server
     */
    protected $server;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @Mapping\OnStart()
     */
    public function onStart()
    {
        $this->em     = $this->container->get('doctrine.orm.entity_manager');
        $this->server = new Server();

        $this->server->setName($this->container->getParameter('vizzle.server'));
        $this->server->setHost(gethostname());
        $this->server->setIp($this->container->get('kernel')->getIp());
        $this->server->setVersion($this->container->get('kernel')->getVersion());
        $this->server->setEnv($this->container->getParameter('kernel.environment'));

        $this->em->persist($this->server);
    }

    /**
     * @Mapping\OnStop()
     */
    public function onStop()
    {
        $this->em->remove($this->server);
        $this->em->flush($this->server);
    }

    /**
     * @Mapping\Execute()
     */
    public function execute()
    {
        $this->server->setUpdatedAt(new \DateTime());
        $this->em->flush($this->server);

        $this->clearOldRow();
    }

    /**
     * Clear old server row.
     */
    public function clearOldRow()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->delete();
        $qb->from('VizzleBundle:Server', 'server');
        $qb->where('server.updatedAt < :date');
        $qb->setParameter('date', (new \DateTime())->sub(new \DateInterval('PT1M')));

        $qb->getQuery()->execute();
    }

    /**
     * Is server monitoring enabled
     */
    public function isEnabled()
    {
        if ($this->container->hasParameter('vizzle.server_monitoring.enabled')) {
            return (boolean)$this->container->getParameter('vizzle.server_monitoring.enabled');
        }

        return true;
    }
}
