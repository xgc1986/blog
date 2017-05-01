<?php
declare(strict_types=1);
namespace Xgc\UtilsBundle\Helper;

use DateTimeZone;

class DateTime extends \DateTime
{

    public static function fromFormat(string $format, $value): DateTime
    {
        $time = intval(\DateTime::createFromFormat($format, strval($value))->format("U"));
        $now = intval((new \DateTime("now"))->format("U"));

        $diff = $time - $now;
        if ($diff === 0) {
            return new DateTime("present");
        } else if ($diff > 0) {
            return new DateTime("+$diff seconds");
        } else {
            return new DateTime("$diff seconds");
        }
    }

    private static function fixTime(string $time): string
    {
        if ($time == "now") {
            $time = Calendar::getInstance()->getOffset();
        } else {
            if ($time == "present") {
                $time = "now";
            }
        }

        return $time;
    }

    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct(self::fixTime($time), $timezone);
    }

    public function getTime(): int
    {
        return intval($this->format("U"));
    }

    public function getRelativeTime(): int
    {
        return $this->getTime() - (new DateTime())->getTime();
    }
}
