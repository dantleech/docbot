<?php

namespace DTL\Docbot\Extension\Core\Renderer;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockDataBuffer;
use Twig\Template;

final class TwigBlockRenderer
{
    public function __construct(private BlockDataBuffer $buffer)
    {
    }

    public function render(Template $template, Block $block): string
    {
        $content = $template->renderBlock($block::name(), [
            'block' => $block,
            'data' => $this->buffer->fetch($block),
        ]);

        return $content;
    }
}
