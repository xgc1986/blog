<?php
declare(strict_types=1);
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use AppBundle\Entity\Setting;
use AppBundle\Entity\User;
use Xgc\CoreBundle\DataFixtures\ORM\Fixture;
use Xgc\CoreBundle\Entity\Entity;
use Xgc\CoreBundle\Service\Settings;
use Xgc\UtilsBundle\Helper\DateTime;

/**
 * @codeCoverageIgnore
 */
class LoadSetting extends Fixture
{
    public function loadTest(): void
    {
        $settingsService = $this->getContainer()->get('settings');
        $settingsService->put('string example', "example");
        $settingsService->putFloat('float example', 8.4);
        $settingsService->putInt('int example', 8);
        $settingsService->putBool('bool example', true);
        $settingsService->putJson('json example', [1, 2, ['a' => 'A', 'b' => 'B']]);
        $settingsService->putDateTime('date example', DateTime::fromFormat('d/m/Y H:i:s', '21/02/1986 12:34:56'));
    }
}
