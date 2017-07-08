<?php
declare(strict_types=1);
namespace Xgc\UtilsBundle\Helper;

use DateTimeZone;

/**
 * Class DateTime
 * @package Xgc\UtilsBundle\Helper
 */
class DateTime extends \DateTime
{

    /**
     * @param \DateTime|null $dateTime
     * @return DateTime|null
     */
    public static function cast(?\DateTime $dateTime): ?DateTime
    {
        if (!$dateTime) {
            return null;
        }
        if (!$dateTime instanceof DateTime) {
            return self::fromFormat("U", $dateTime->format("U"));
        }

        return $dateTime;
    }

    /**
     * @param string $format
     * @param $time
     * @return DateTime
     */
    public static function fromFormat(string $format, $time): DateTime
    {
        $time = intval(\DateTime::createFromFormat($format, strval($time))->format("U"));
        $now = intval((new \DateTime("now"))->format("U"));

        $diff = $time - $now;
        if ($diff === 0) {
            return new DateTime("present");
        }
        else if ($diff > 0) {
            return new DateTime("+$diff seconds");
        }
        else {
            return new DateTime("$diff seconds");
        }
    }

    /**
     * @param $time
     * @return string
     */
    private static function fixTime(string $time): string
    {
        if ($time == "now") {
            $time = Calendar::getInstance()->getOffset();
        }
        else {
            if ($time == "present") {
                $time = "now";
            }
        }

        return $time;
    }

    /**
     * @param $time
     * @return DateTime
     */
    public static function fromExcel(int $time): DateTime
    {
        $timestamp = ($time - 25569) * 86400;

        return DateTime::fromFormat("U", $timestamp);
    }

    /**
     * @param int $week
     * @return DateTime
     */
    public static function fromWeek(int $week): DateTime
    {
        $weekStart = new DateTime();
        $weekStart->getOriginal()->setISODate($weekStart->format("Y"), $week % 52);

        return $weekStart;
    }

    /**
     * @param int $day
     * @return DateTime
     */
    public static function fromDay(int $day): DateTime
    {
        $year = DateTime::create()->format("Y");

        return DateTime::fromFormat("z", DateTime::fromFormat("z", $day)->format("z"));
    }

    /**
     * DateTime constructor.
     * @param string $time
     * @param DateTimeZone|null $timezone
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct(self::fixTime($time), $timezone);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format("d-m-Y H:i:s");
    }

    /**
     * @param DateTime|null $date
     * @return int
     */
    public function getDiffInSeconds(?DateTime $date = null): int
    {
        $date = $date ?? new DateTime;

        $diffValue = $date->getTime() - $this->getTime();

        return intval($diffValue);
    }

    /**
     * @param DateTime|null $date
     * @return int
     */
    public function getDiffInMinutes(?DateTime $date = null): int
    {
        $diffValue = floor($this->getDiffInSeconds($date) / 60);

        return intval($diffValue);
    }

    /**
     * @param DateTime|null $date
     * @return int
     */
    public function getDiffInDays(?DateTime $date = null): int
    {
        $date = $date ?? new DateTime;
        $diff = $this->diff($date);

        $diffValue = $diff->format("%a");

        return intval($diffValue);
    }

    /**
     * @param DateTime|null $date
     * @return int
     */
    public function getDiffInWeeks(?DateTime $date = null): int
    {
        $date = $date ?? new DateTime;
        $diff = $this->diff($date);

        $diffValue = floor($diff->format("%a") / 7);

        return intval($diffValue);
    }

    /**
     * @param DateTime|null $date
     * @return int
     */
    public function getDiffInMonths(?DateTime $date = null): int
    {
        $date = $date ?? new DateTime;
        $diff = $this->diff($date);

        $diffValue = $diff->format("%m") + 12 * $this->getDiffInYears($date);

        return intval($diffValue);
    }

    /**
     * @param DateTime|null $date
     * @return int
     */
    public function getDiffInYears(?DateTime $date = null): int
    {
        $date = $date ?? new DateTime;
        $diff = $this->diff($date);

        $diffValue = $diff->format("%y");

        return intval($diffValue);
    }

    /**
     * @return int
     */
    public function getAbsoluteDay(): int
    {
        return intval($this->format('z'));
    }

    /**
     * @return int
     */
    public function getAbsoluteWeek(): int
    {
        return intval($this->format('W'));
    }

    /**
     * @return int
     */
    public function toExcel(): int
    {
        return intval(round(25569 + $this->format("U") / 86400));
    }

    /**
     * @return DateTime
     */
    public function roundDate(): DateTime
    {
        return DateTime::fromFormat("d/m/Y h:i:s", $this->format("d/m/Y 00:00:00"));
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return intval($this->format("U"));
    }

    /**
     * @param int $time
     * @return DateTime
     */
    public function addSeconds(int $time): DateTime
    {
        return $this->add(new \DateInterval("PT{$time}S"));
    }

    /**
     * @param int $time
     * @return DateTime
     */
    public function subSeconds(int $time): DateTime
    {
        return $this->sub(new \DateInterval("PT{$time}S"));
    }

    /**
     * if Date is < retorna >0
     *
     * @param DateTime $date
     * @return int
     */
    public function compare(DateTime $date): int
    {
        return $this->getTime() - $date->getTime();
    }

    /**
     * @return bool
     */
    public function hasExpired(): bool
    {
        return $this->compare(new DateTime) <= 2;
    }

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @return bool
     */
    public function isBetween(DateTime $from, DateTime $to): bool
    {
        return (
            $this->compare($from) >= 0 &&
            $this->compare($to) <= 0
        );
    }

}
