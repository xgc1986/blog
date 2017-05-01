<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Validator\Constraints\Date;
use Xgc\CoreBundle\Entity\Setting;
use Xgc\CoreBundle\Exception\Settings\InvalidReadSettingException;
use Xgc\CoreBundle\Exception\Settings\InvalidWriteSettingException;
use Xgc\CoreBundle\Exception\Settings\SettingNotFoundException;
use Xgc\UtilsBundle\Helper\DateTime;

class SettingsService
{

    const STRING   = "STRING";
    const INT      = "INT";
    const FLOAT    = "FLOAT";
    const JSON     = "JSON";
    const BOOL     = "BOOL";
    const DATETIME = "DATETIME";

    protected $doctrine;
    protected $validator;

    function __construct(Registry $doctrine, ValidatorService $validator)
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    public function get(string $key, string $default = ''): string
    {
        return $this->load($key, self::STRING) ?? $default;
    }

    public function put(string $key, string $value): void
    {
        $this->save($key, self::STRING, $value);
    }

    public function getInt(string $key, int $default = 0): int
    {
        return $this->load($key, self::INT) ?? $default;
    }

    public function putInt(string $key, int $value): void
    {
        $this->save($key, self::INT, "$value");
    }

    public function getFloat(string $key, float $default = 0.0): float
    {
        return $this->load($key, self::FLOAT) ?? $default;
    }

    public function putFloat(string $key, float $value): void
    {
        $this->save($key, self::FLOAT, "$value");
    }

    public function getBool(string $key, bool $default = false): bool
    {
        return $this->load($key, self::BOOL) ?? $default;
    }

    public function putBool(string $key, bool $value): void
    {
        $this->save($key, self::BOOL, $value? "true" : "false");
    }

    public function getJson(string $key, array $default = []): array
    {
        return $this->load($key, self::JSON) ?? $default;
    }

    public function putJson(string $key, array $value): void
    {
        $this->save($key, self::JSON, json_encode($value));
    }

    public function getDateTime(string $key, DateTime $default = null): DateTime
    {
        return $this->load($key, self::DATETIME) ?? $default;
    }

    public function putDateTime(string $key, DateTime $value): void
    {
        $this->save($key, self::DATETIME, $value->format('U'));
    }

    public function isValid(string $key, string $type)
    {
        $repo = $this->doctrine->getRepository("XgcCoreBundle:Setting");
        $setting = $repo->findOneBy(['$key' => $key]);

        if ($setting) {
            return $setting->getType() === $type;
        }

        return true;
    }

    public function remove(string $key)
    {
        $repo = $this->doctrine->getRepository("XgcCoreBundle:Setting");
        $setting = $repo->findOneBy(['key' => $key]);
        if ($setting) {
            $this->doctrine->getManager()->remove($setting);
        }
    }

    public function commit()
    {
        $this->doctrine->getManager()->flush();
    }

    private function save(string $key, string $type, string $value): void
    {
        $repo = $this->doctrine->getRepository("XgcCoreBundle:Setting");
        $setting = $repo->findOneBy(['key' => $key]);

        if ($setting) {
            if ($setting->getType() === $type) {
                $setting->setValue($value);
            } else {
                throw new InvalidWriteSettingException($key, $setting->getType());
            }
        } else {
            $setting = new Setting();
            $setting->setKey($key);
            $setting->setType($type);
            $setting->setValue($value);

            $this->validator->validate($setting);
            $this->doctrine->getManager()->persist($setting);
        }
    }

    /**
     * @param string $key
     * @param string $type
     * @return string|int|bool|float|DateTime|array
     */
    private function load(string $key, string $type)
    {
        $repo = $this->doctrine->getRepository("XgcCoreBundle:Setting");
        $setting = $repo->findOneBy(['key' => $key]);

        if ($setting) {
            if ($setting->getType() === $type) {
                return $setting->getRealValue();
            } else {
                throw new InvalidReadSettingException($key, $setting->getType());
            }
        } else {
            throw new SettingNotFoundException($key);
        }
    }
}
