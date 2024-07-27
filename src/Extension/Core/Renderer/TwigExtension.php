<?php

namespace DTL\Docbot\Extension\Core\Renderer;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockRenderer;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TwigExtension extends AbstractExtension
{
    public function __construct(
        private TwigBlockRenderer $renderer,
        private string $format,
    )
    {
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_block', $this->renderBlock(...), [
                'needs_environment' =>  true,
            ]),
        ];
    }

    private function renderBlock(Environment $env, Block $block): string
    {
        $template = $env->load(sprintf('%s.twig', $this->format));
        return $this->renderer->render($template->unwrap(), $block);
    }
}
