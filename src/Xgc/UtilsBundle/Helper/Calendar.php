<?php
declare(strict_types=1);
namespace Xgc\UtilsBundle\Helper;

class Calendar
{
    /**
     * @var Calendar
     */
    private static $instance;

    /**
     * @var int
     */
    private $now = 0;

    public static function now() : DateTime
    {
        return new DateTime;
    }

    public static function present() : DateTime
    {
        return new DateTime("present");
    }

    public static function getInstance(): Calendar
    {
        self::$instance = self::$instance ?? new Calendar;
        return self::$instance;
    }

    public function travel(DateTime $to)
    {
        $now = intval((new DateTime("present"))->format('U'));
        $travelTo = intval($to->format("U"));

        $this->now = $travelTo - $now;
    }

    public function backToPresent()
    {
        $this->now = 0;
    }

    public function getOffset(): string
    {
        if ($this->now === 0) {
            return "now";
        } else if ($this->now >= 0) {
            return "+$this->now seconds";
        } else {
            return "$this->now seconds";
        }
    }
}
