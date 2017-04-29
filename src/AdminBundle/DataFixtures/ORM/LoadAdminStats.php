<?php
declare(strict_types=1);
namespace AppBundle\DataFixtures\ORM;

use AdminBundle\Entity\AdminStats;
use Xgc\CoreBundle\DataFixtures\ORM\Fixture;

/**
 * @codeCoverageIgnore
 */
class LoadAdminStats extends Fixture
{
    public function loadDev(): void
    {
        $this->persist(new AdminStats(1));
    }
}
