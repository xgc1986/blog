<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Xgc\UtilsBundle\Helper\DateTime;

class DateTimeType extends \Doctrine\DBAL\Types\DateTimeType
{
    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }

        $val = DateTime::fromFormat($platform->getDateTimeFormatString(), $value);

        if ( ! $val) {
            $val = date_create($value);
        }

        if ( ! $val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        return $val;
    }
}
