<?php

namespace DTL\Docbot\Model\Error;

use RuntimeException;

final class AssertionFailed extends RuntimeException
{
    public static function create(string $message)
    {
        return new self($message);
    }
}
