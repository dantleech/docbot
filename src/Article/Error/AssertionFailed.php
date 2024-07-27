<?php

namespace DTL\Docbot\Article\Error;

use RuntimeException;

final class AssertionFailed extends RuntimeException
{
    public static function create(string $message): self
    {
        return new self($message);
    }
}
