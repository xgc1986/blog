<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use PackageVersions\Versions as Vendors;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Versions
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFirstParties()
    {
        $selfVersions = $this->container->getParameter('xgc.versions');
        ksort($selfVersions);

        return $selfVersions;
    }

    public function getThirdParties(): array
    {
        $versions = Vendors::VERSIONS;
        ksort($versions);

        return $versions;
    }
}
