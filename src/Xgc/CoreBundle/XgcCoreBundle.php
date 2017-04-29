<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Xgc\CoreBundle\DependencyInjection\XgcCoreExtension;

class XgcCoreBundle extends Bundle
{
    public function getContainerExtension()
    {
        $this->extension = $this->extension ?? new XgcCoreExtension;
        return $this->extension;
    }
}
