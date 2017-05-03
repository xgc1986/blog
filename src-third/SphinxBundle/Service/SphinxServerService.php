<?php
declare(strict_types=1);
namespace Xgc\SphinxBundle\Service;

class SphinxServerService
{
    protected $verbose;

    public function validConf(): bool
    {

    }

    public function getStatus(): bool
    {
        return false;
    }

    public function start(): bool
    {
        if ($this->getStatus()) {
            // TODO throw exception
        }

        return $this->start();
    }

    public function restart(): bool
    {
        if ($this->getStatus()) {
            $this->stop();
            $this->start();
        } else {
            $this->start();
        }
        return $this->getStatus();
    }

    public function stop(): bool
    {
        return false;
    }

    public function update(): bool
    {
        if (!$this->getStatus()) {
            return false;
        }

        return false;
    }

    public function getVerbose(): string
    {
        return $this->verbose;
    }


}
