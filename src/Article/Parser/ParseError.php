<?php

namespace DTL\Docbot\Article\Parser;

use RuntimeException;
use Throwable;

final class ParseError extends RuntimeException
{
    public function __construct(public string $filename, Throwable $error)
    {
        parent::__construct(sprintf('Could not parse file %s: %s', $filename, $error->getMessage()), 0, $error);
    }
}
