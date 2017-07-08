<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class Symfony extends ContainerService
{

    /**
     * @var KernelInterface
     */
    private static $kernel;

    /**
     * @return string
     */
    public function getRoot(): string
    {
        return realpath($this->loadKernel()->getRootDir() . '/..');
    }

    /**
     * @return KernelInterface
     */
    public function loadKernel(): KernelInterface
    {
        if (self::$kernel == null) {
            global $kernel;
            if ($kernel instanceOf \AppCache) {
                $kernel = $kernel->getKernel();
            }

            return $kernel;
        }

        return self::$kernel;
    }

    /**
     * @return string
     */
    public function getEnv(): string
    {
        return $this->container->getParameter("kernel.environment");
    }
}
