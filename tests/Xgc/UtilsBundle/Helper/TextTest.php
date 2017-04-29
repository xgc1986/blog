<?php
declare(strict_types=1);
namespace Test\Xgc\UtilsBundle\Entity;

use PHPUnit\Framework\TestCase;
use Xgc\UtilsBundle\Helper\Text;

/**
 * @codeCoverageIgnore
 */
class TextTest extends TestCase
{
    public function testPassword()
    {
        static::assertFalse(Text::validatePassword("ñçñçñçñ123"));
        static::assertFalse(Text::validatePassword("1234"));
        static::assertFalse(Text::validatePassword("qwerty"));
        static::assertTrue(Text::validatePassword("12ab"));
        static::assertFalse(Text::validatePassword("áa", 4, false, false, false, false));
        static::assertFalse(Text::validatePassword("aas", 4, false, false, false, false));
        static::assertTrue(Text::validatePassword("aasa", 4, false, false, false, false));
        static::assertFalse(Text::validatePassword("aasa", 6, false, false, false, false));
        static::assertTrue(Text::validatePassword("aasa34", 6, false, false, false, false));
        static::assertFalse(Text::validatePassword("áááááçñ", 6, true, false, false, false));
        static::assertTrue(Text::validatePassword("lowercasetext", 6, true, false, false, false));
        static::assertFalse(Text::validatePassword("lowercasetext", 6, true, false, true, false));
        static::assertFalse(Text::validatePassword("UPPERCASETEXT", 6, true, false, true, false));
        static::assertTrue(Text::validatePassword("camelCase", 6, true, false, true, false));
        static::assertTrue(Text::validatePassword("as@{a334SEas_", 6, true, false, false, false));
        static::assertFalse(Text::validatePassword("camelCase", 6, true, true, true, false));
        static::assertTrue(Text::validatePassword("camelCase1", 6, true, true, true, false));
        static::assertFalse(Text::validatePassword("camelCase1", 6, true, true, true, true));
        static::assertTrue(Text::validatePassword("as@{a334SEas_", 6, true, true, true, true));
        static::assertFalse(Text::validatePassword("123@#ñç_.*", 6, false, true, true, true));
        static::assertFalse(Text::validatePassword("123@#ñç_.*", 6, true, true, false, true));
        static::assertFalse(Text::validatePassword("123@#_.*", 6, true, true, false, true));
    }

    public function testValidateEmail()
    {
        static::assertTrue(Text::validateEmail("xgc1986@gmail.com"));
        static::assertFalse(Text::validateEmail("x.g.c...._1986@gmail.com"));
        static::assertTrue(Text::validateEmail("xgc.j_1986@gmail.com"));
        static::assertFalse(Text::validateEmail("xgc1986@gmailcom"));
        static::assertFalse(Text::validateEmail("xgc1986gmail.com"));
        static::assertFalse(Text::validateEmail("xgc1986@gmail."));
        static::assertFalse(Text::validateEmail("xgc1986@.com"));
        static::assertFalse(Text::validateEmail("xgc1986@."));
        static::assertFalse(Text::validateEmail("xgc1986@@gmail.com"));
        static::assertTrue(Text::validateEmail("xgc{}1986@gmail.com"));
    }

    public function testCleanEmail()
    {
        static::assertTrue(Text::validateEmail("xgc1986@gmail.com"));
        static::assertFalse(Text::validateEmail("x.g.c...._1986@gmail.com"));
        static::assertTrue(Text::validateEmail("xgc.j_1986@gmail.com"));
        static::assertFalse(Text::validateEmail("xgc1986@gmailcom"));
        static::assertFalse(Text::validateEmail("xgc1986gmail.com"));
        static::assertFalse(Text::validateEmail("xgc1986@gmail."));
        static::assertFalse(Text::validateEmail("xgc1986@.com"));
        static::assertFalse(Text::validateEmail("xgc1986@."));
        static::assertFalse(Text::validateEmail("xgc1986@@gmail.com"));
        static::assertTrue(Text::validateEmail("xgc{}1986@gmail.com"));
    }
}
