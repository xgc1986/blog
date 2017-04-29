<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Xgc\CoreBundle\Entity\User;

class SymfonyHelper
{
    private static $instance;
    protected $kernel;

    public static function getInstance(): SymfonyHelper
    {
        self::$instance = self::$instance ?? new SymfonyHelper();

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function getKernel(): KernelInterface
    {
        global $kernel;

        return $kernel?? $this->kernel;
    }

    public function setKernel(KernelInterface $kernel): void
    {
        $this->kernel = $kernel;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->getKernel()->getContainer();
    }

    public function getUser(): ?User
    {
        return $this->getContainer()->get('xgc.security')->getUser();
    }

    public function getRoot(): string
    {
        $dir = getcwd();
        return preg_replace("/(.*)(\/web.*)/", "$1", $dir);
    }
}
