<?php

namespace DTL\Docbot\Extension\Core\Renderer;

use DTL\Docbot\Article\Block;
use Twig\Template;

final class TwigBlockRenderer
{
    public function render(Template $template, Block $block): string
    {
        $content = $template->renderBlock($block::name(), [
            'block' => $block,
        ]);

        return $content;
    }
}
