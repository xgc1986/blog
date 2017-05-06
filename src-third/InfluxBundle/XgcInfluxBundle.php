<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Xgc\InfluxBundle\DependencyInjection\XgcInfluxExtension;

class XgcInfluxBundle extends Bundle
{
    public function getContainerExtension()
    {
        $this->extension = $this->extension ?? new XgcInfluxExtension();

        return $this->extension;
    }
}
