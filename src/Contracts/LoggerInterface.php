<?php

namespace Tfo\AdvancedLog\Contracts;

interface LoggerInterface
{
    public function log(string $level, string $message, array $context = []): void;
}