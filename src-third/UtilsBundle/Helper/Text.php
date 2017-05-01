<?php
declare(strict_types=1);
namespace Xgc\UtilsBundle\Helper;

class Text
{
    private const ALL = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#\/-_$%^&+=!?()[]{},.;:';
    private const LETTERS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const LOWERS = 'abcdefghijklmnopqrstuvwxyz';
    private const UPPERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const DIGITS = '0123456789';
    private const SYMBOLS = '#\/-_$%^&+=!?()[]{},.;:';

    public static function validatePassword(
        string $hash,
        int $minLength = 4,
        bool $letters = true,
        bool $digits = true,
        bool $cases = false,
        bool $symbols = false
    ): bool {

        for ($idx = 0; $idx < strlen($hash); $idx++) {
            if (strpos(self::ALL, $hash[$idx]) === false) {
                return false;
            }
        }

        if (mb_strlen($hash) < $minLength) {
            return false;
        }

        if ($letters && !self::containsAtLeastOne($hash, self::LETTERS)) {
            return false;
        }

        if ($digits && !self::containsAtLeastOne($hash, self::DIGITS)) {
            return false;
        }

        if ($cases && !(self::containsAtLeastOne($hash, self::LOWERS) && self::containsAtLeastOne($hash, self::UPPERS))) {
            return false;
        }

        if ($symbols && !self::containsAtLeastOne($hash, self::SYMBOLS)) {
            return false;
        }

        return true;
    }

    private static function containsAtLeastOne(string $needle, string $haystack): bool
    {
        for ($idx = 0; $idx < strlen($needle); $idx++) {
            if (strpos($haystack, $needle[$idx])) {
                return true;
            }
        }

        return false;
    }

    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) != null;
    }

    public static function rstr(int $length = 8): string
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $count = strlen($chars);

        $bytes = random_bytes($length);
        $result = '';
        foreach (str_split($bytes) as $byte) {
            $result .= $chars[ord($byte) % $count];
        }

        return $result;
    }
}
