<?php
declare(strict_types=1);
namespace Xgc\UtilsBundle\Helper;

use DateTimeZone;

class DateTime extends \DateTime
{

    public static function fromFormat(string $format, $value): DateTime
    {
        if ($format === 'U') {
            $timeDate = \DateTime::createFromFormat('U', $value)->setTimezone(new \DateTimeZone('Europe/Paris'));
        } else {
            $timeDate = \DateTime::createFromFormat($format, strval($value));
        }
        $time = intval($timeDate->format("U"));

        $nowDate = new \DateTime("now");
        $now = intval($nowDate->format("U"));

        $diff = $time - $now;

        /**
         * DST adjustement
         */
        if ($nowDate->format('I') !== $timeDate->format('I')) {
            if ($timeDate->format('I') === "1") {
                $diff += 3600;
            } else {
                $diff -= 3600;
            }
        }

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
        return intval($this->getTimestamp());
    }

    public function getMilis(): int
    {
        return $this->getTime() * 1000 + intval($this->format('v'));
    }

    public function getMicros(): int
    {
        return $this->getTime() * 1000000 + intval(substr(microtime(), 2, 6));
    }

    public function getRelativeTime(): int
    {
        return $this->getTime() - (new DateTime())->getTime();
    }

    public function dst(): bool
    {
        return $this->format('I') === "1";
    }
}
