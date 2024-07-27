<?php

namespace DTL\Docbot\Extension\Core\Renderer;

use DTL\Docbot\Article\Block;
use DTL\Docbot\Renderer\TokenReplacer;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class TwigExtension extends AbstractExtension
{
    public function __construct(
        private TwigBlockRenderer $renderer,
        private TokenReplacer $replacer,
        private string $format,
    ) {
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_block', $this->renderBlock(...), [
                'needs_environment' =>  true,
            ]),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('sub_block_tokens', $this->subBlockTokens(...), [
            ]),
        ];
    }

    private function renderBlock(Environment $env, Block $block): string
    {
        $template = $env->load(sprintf('%s.twig', $this->format));
        return $this->renderer->render($template->unwrap(), $block);
    }

    private function subBlockTokens(string $subject, ?object $context = null): string
    {
        if (null === $context) {
            return $subject;
        }
        return $this->replacer->replace($subject, $context);
    }
}
