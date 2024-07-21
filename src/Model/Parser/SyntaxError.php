<?php

namespace Dantleech\Exedoc\Model\Parser;

use RuntimeException;

final class SyntaxError extends RuntimeException
{
    public function __construct(
        public string $contents,
        public int $startLine,
        string $message
    )
    {
        parent::__construct(sprintf('Syntax error on line %d: %s', $startLine, $message));
    }
}
