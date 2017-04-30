<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Setting extends Entity
{
    /**
     * @var string
     * @Assert\Length(
     *     min = 1,
     *     max = 16
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
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }
}
