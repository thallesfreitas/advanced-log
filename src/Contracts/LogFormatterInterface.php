<?php

namespace Tfo\AdvancedLog\Contracts;

interface LogFormatterInterface extends \Monolog\Formatter\FormatterInterface
{
    public function format(string $level, string $message, array $context = []): array;

}