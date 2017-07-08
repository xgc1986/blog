<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\Entity;

use Xgc\InfluxBundle\Annotation\Measurement;
use Xgc\InfluxBundle\Annotation\Tag;
use Xgc\UtilsBundle\Helper\DateTime;

/**
 * @Measurement("log")
 */
class Log extends MeasurementEntity
{
    const INFO    = "INFO";
    const DEBUG   = "DEBUG";
    const WARNING = "WARNING";
    const ERROR   = "ERROR";

    /**
     * @Tag
     */
    protected $tag;

    /**
     * @Tag
     */
    protected $username;

    /**
     * @Tag
     */
    protected $level;

    /**
     * @Tag
     */
    protected $message;

    public function __construct(?DateTime $date = null)
    {
        parent::__construct($date);
    }

    public static function create(string $tag, string $user, string $type, string $message = ''): Log
    {
        $ret = new Log();
        $ret->setLevel($type);
        $ret->setMessage($message);
        $ret->setTag($tag);
        $ret->setUsername($user);

        return $ret;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @param string $level
     */
    public function setLevel(string $level): void
    {
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function __getType(): string
    {
        return 'log';
    }
}
