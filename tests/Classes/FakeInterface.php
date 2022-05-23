<?php

declare(strict_types=1);

namespace Ray\RayDiForLaravel\Classes;

interface FakeInterface
{
    public function __invoke(): void;
}
