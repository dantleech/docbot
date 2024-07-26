<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Model\Block;

final readonly class CreateFileBlock implements Block
{
    public function __construct(
        public string $path,
        public string $language,
        public string $content,
    ) {
    }

    public function describe(): string
    {
        return sprintf(
            'Creating %s file at "%s"',
            $this->language,
            $this->path
        );
    }
}
