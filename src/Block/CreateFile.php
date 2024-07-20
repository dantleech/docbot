<?php

namespace Dantleech\Exedoc\Block;

use Dantleech\Exedoc\Model\Block;

final readonly class CreateFile implements Block
{
    public function __construct(
        public string $path,
        public string $language,
        public string $contents
    )
    {
    }
}
