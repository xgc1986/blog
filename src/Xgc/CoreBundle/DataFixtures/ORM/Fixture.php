<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Xgc\CoreBundle\Entity\Entity;

/**
 * @codeCoverageIgnore
 */
abstract class Fixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    private static $map = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ObjectManager
     */
    private $manager;

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        if ($this->getEnv() === "prod") {
            $this->loadProd();
        }

        $this->manager->flush();

        if ($this->getEnv() === "dev") {
            $this->loadProd();
            $this->loadDev();
        }

        $this->manager->flush();

        if ($this->getEnv() === 'test') {
            $this->loadProd();
            $this->loadDev();
            $this->loadTest();
        }

        $this->manager->flush();
    }

    public function getEnv(): string
    {
        return $this->container->getParameter('kernel.environment');
    }

    public function loadProd(): void
    {
    }

    public function loadDev(): void
    {
    }

    public function loadTest(): void
    {
    }

    /**
     * @param string $key
     * @return Entity|object
     */
    public function get(string $key): Entity
    {
        return $this->getReference($key);
    }

    public function persist(Entity $entity, ?string $key = null): void
    {
        $this->manager->persist($entity);
        if ($key) {
            $this->addReference($key, $entity);
        }
    }

    public function flush(): void
    {
        $this->manager->flush();
    }
}
