<?php
declare(strict_types=1);
namespace Xgc\UtilsBundle\Helper;

class PHP
{
    public static function UPLOAD_MAX_FILESIZE()
    {
        $postMaxSize = self::POST_MAX_SIZE();
        $uploadMaxSize = self::parseSize(ini_get('upload_max_filesize'));
        return min($postMaxSize, $uploadMaxSize);
    }

    public static function POST_MAX_SIZE(): int
    {
        return self::parseSize(ini_get('post_max_size'));
    }

    private static function parseSize(string $size): int
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);

        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return (int) round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return (int) round($size);
        }
    }
}
