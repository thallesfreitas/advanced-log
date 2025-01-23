<?php

namespace Tfo\AdvancedLog\Contracts;

interface LogFormatterInterface
{
    public function format(string $level, string $message, array $context = []): array;

}