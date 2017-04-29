<?php
declare(strict_types=1);
namespace Test\Xgc\UtilsBundle\Helper;

use PHPUnit\Framework\TestCase;
use Xgc\UtilsBundle\Helper\Calendar;
use Xgc\UtilsBundle\Helper\DateTime;

class CalendarTest extends TestCase
{
    public function testTravel()
    {
        // present
        $present = Calendar::now();
        $futureTime = $present->getTime() + 31556952;// 1 year

        // travel to 1 year to the future
        $calendar = Calendar::getInstance();
        $calendar->travel(DateTime::fromFormat("U", $futureTime));
        $future = Calendar::now();
        $presentYear2 = intval(Calendar::present()->format("Y"));
        $presentYear = intval($present->format("Y"));
        $futureYear = intval($future->format("Y"));
        self::assertEquals($presentYear + 1, $futureYear);
        self::assertEquals($presentYear2 + 1, $futureYear);
        $anotherFuture = new DateTime;
        self::assertEquals($futureYear, $anotherFuture->format("Y"));

        // back to present
        $calendar->backToPresent();
        $anotherPresent = new DateTime;
        self::assertEquals($presentYear, $anotherPresent->format("Y"));
    }

    public function testPastTravel()
    {
        $present = new DateTime;
        $pastTime = $present->getTime() - 31556952;// 1 year

        $calendar = Calendar::getInstance();
        $calendar->travel(DateTime::fromFormat("U",$pastTime));

        $past = new DateTime;

        $presentYear = intval($present->format("Y"));
        $pastYear = intval($past->format("Y"));

        self::assertEquals($pastYear + 1, $presentYear);


    }

    public function testMissing()
    {
        $now = new DateTime;
        $calendar = Calendar::getInstance();
        $calendar->travel($now); // technically you trave some milis to the past
        $nowAgain = new DateTime;
        self::assertEquals($now->getTime(), $nowAgain->getTime());
    }

    public function testMissing2()
    {
        $now = new DateTime;
        $nowAgain = DateTime::fromFormat("U", $now->getTime());
        self::assertEquals($now->getTime(), $nowAgain->getTime());

    }

    protected function tearDown()
    {
        parent::tearDown();
        Calendar::getInstance()->backToPresent();
    }
}
