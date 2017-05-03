<?php
declare(strict_types=1);
namespace Xgc\SphinxBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Xgc\SphinxBundle\DependencyInjection\XgcSphinxExtension;

class XgcSphinxBundle extends Bundle
{
    public function getContainerExtension()
    {
        $this->extension = $this->extension ?? new XgcSphinxExtension();

        return $this->extension;
    }
}
