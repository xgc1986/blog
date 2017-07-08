<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Xgc\CoreBundle\Service\Settings;
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
        $ret          = parent::__toArray();
        $ret['key']   = $this->getKey();
        $ret['type']  = $this->getType();
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
        switch($this->type) {
            case Settings::DATETIME:
                return DateTime::fromFormat('U', $this->value);
            case Settings::STRING:
                return $this->value;
            case Settings::INT:
                return intval($this->value);
            case Settings::FLOAT:
                return floatval($this->value);
            case Settings::JSON:
                return json_decode($this->value, true);
            case Settings::BOOL:
                return $this->value === "true" || $this->value === "1";
            default:
                return null;
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
