<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\Entity;

use Xgc\UtilsBundle\Helper\DateTime;
use Xgc\UtilsBundle\Helper\JSON;

/*
 * @Measurement('my_measurement')
 */
abstract class InfluxEntity implements JSON
{
    /*



    /**
     * @Tag
     *
    protected myTag;

    /**
     * @Tag
     *
    protected myTag2;

    /**
     * @Field
     *
    protected myField;

    /**
     * @Field
     *
    protected myField2;


     */

    /**
     * @var DateTime
     */
    protected $timeStamp;

    public function __toArray(): array
    {
        return [
            'id' => $this->getId(),
            'timeStamp' => $this->getTimeStamp()->format('U'),
            'fields' => [],
            'tags' => []
        ];
    }

    public function __getType(): string
    {
        // TODO
    }

    public function getId(): int
    {
        $this->getTimeStamp()->getTime();
    }

    public function getTimeStamp(): DateTime
    {
        return new DateTime();
    }
}
