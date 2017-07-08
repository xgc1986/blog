<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
