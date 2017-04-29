<?php
declare(strict_types=1);
namespace Xgc\UtilsBundle\Helper;

class File
{
    public static function isImage($file): bool
    {
        return exif_imagetype($file) !== false;
    }
}
