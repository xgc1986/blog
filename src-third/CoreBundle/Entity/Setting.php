<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Xgc\CoreBundle\Service\SettingsService;
use Xgc\UtilsBundle\Helper\DateTime;

class Setting extends Entity
{
    /**
     * @var string
     * @Assert\Length(
     *     min = 1,
     *     max = 32
     * )
     */
    protected $key;

    /**
     * @var string
     * @Assert\Length(
     *     min = 3,
     *     max = 8
     * )
     */
    protected $type;

    /**
     * @var string
     * @Assert\Length(
     *     min = 0,
     *     max = 256
     * )
     */
    protected $value;

    function __toArray(): array
    {
        $ret = parent::__toArray();
        $ret['key'] = $this->getKey();
        $ret['type'] = $this->getType();
        $ret['value'] = $this->getValue();

        return $ret;
    }

    public function __getType(): string
    {
        return 'role';
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string|int|bool|float|DateTime|array
     */
    public function getRealValue()
    {
        if ($this->type == SettingsService::DATETIME) {
            return DateTime::fromFormat('U', $this->value);
        }

        if ($this->type == SettingsService::STRING) {
            return $this->value;
        }

        if ($this->type == SettingsService::INT) {
            return intval($this->value);
        }

        if ($this->type == SettingsService::FLOAT) {
            return floatval($this->value);
        }

        if ($this->type == SettingsService::JSON) {
            return json_decode($this->value, true);
        }

        if ($this->type == SettingsService::BOOL) {
            return $this->value === "true";
        }
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }
}
