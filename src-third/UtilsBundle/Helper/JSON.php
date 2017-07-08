<?php
declare(strict_types=1);
namespace Xgc\UtilsBundle\Helper;

interface JSON
{
    public function __toArray(): array;

    public function __getType(): string;

    public function getId(): ?int;
}
